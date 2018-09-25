<?php

namespace App\Modules\Users\Application\Controllers;

use App\Http\Controllers\Controller;
use App\Transformers\ResponseTransformer;
use Illuminate\Http\Request;

use App\Modules\Users\Domain\Manager;

use App\Validations\Users\UserName;
use App\Validations\Users\Password;

use App\Jobs\User\ForgetPwdEmail;

class Account extends Controller {

    protected $manager;
    
    public function __construct () { 
        $this->manager = (new Manager);
    }

    /**
     * Method to send Email on forget password
     * 
     * @access any
     * 
     * @api users/forget-password
     * @method  POST
     * 
     * @success-format: {"status":"success","title":"User","message":"Please check your Email, We sent you a link to recover your password.","data":{}}
     * 
     * @return ResponseTransformer
     */
    public function forgetPassword (UserName $validator, Request $request) {
        // check for any error if occur
        if ($validation = $validator->validate ($request->all())) {
            return ResponseTransformer::response (false, 'User', 'parameter errors', $validation->messages()->toArray(), 422);
        }

        // call queue-job to send Email to user for password reset link
        dispatch (new ForgetPwdEmail ($request->username, $request->fullUrl()));

        return ResponseTransformer::response (true, 'User', 'Please check your Email, We sent you a link to recover your password.');
    }

    /**
     * Method to Reset forget password
     * 
     * @access any
     * 
     * @api users/forget-password/{token}
     * @method  POST
     * 
     * @success-format: {"status":"success","title":"Forget Password","message":"Password successfully updated.","data":{}}
     * 
     * @return ResponseTransformer
    */
    public function resetPassword (Password $validator, string $token, Request $request) {
        // check for any error if occur
        if ($validation = $validator->validate ($request->all())) {
            return ResponseTransformer::response (false, 'User', 'parameter errors', $validation->messages()->toArray(), 422);
        }

        // search for user Token
        $verification = $this->manager->findUserByVerification ('email', $token);

        // check if user token found
        if (!$verification) {
            return ResponseTransformer::response (false, 'Verification', 'Invalid Token', ["Invalid Token found"], 422);
        }

        // fetch user detail
        $user = $verification->user()->first();

        // verify User Email
        $this->manager->updateUserProfile ($user, ['email_verified' => true]);

        // update user-password
        if ($this->manager->updateUser ($user, $request->only ('password'))) {

            // delete verification link
            $verification->delete();

            return ResponseTransformer::response (true, 'Forget Password', 'Password successfully updated.');
        }

        return ResponseTransformer::response (false, 'Verification');
    }
}