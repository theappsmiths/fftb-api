<?php

namespace App\Modules\Users\Application\Controllers;

use App\Http\Controllers\Controller;
use App\Transformers\ResponseTransformer;
use Illuminate\Http\Request;

use App\Validations\Users\Register;
use App\Validations\Users\Profile;
use App\Validations\Users\Device;
use App\Validations\Users\ProfileUpdate;
use App\Validations\Users\Password;

use App\Modules\Users\Domain\Manager;

class User extends Controller {
    
    public function __construct () { }

    /**
     * Method to register a user
     * 
     * @api users
     * @method  POST
     * 
     * @success-format: {"status":"success","title":"user","message":"user successfully registered","data":{}}
     * 
     * @access any
     * 
     * @table:  tbl_users
     * @table:  tbl_user_device_detail
     * @table:  tbl_user_profile
     * 
     * @return ResponseTransformer
     */
    public function register (
        Register $validator, 
        Profile $validator1, 
        Device $validator2, 
        Request $request,
        Manager $manager
    ) {
        // fetch device required field from header
        $requestHeader = [
            'deviceToken' => $request->header('deviceToken'),
            'deviceType' => $request->header('deviceType'),
            'fcmToken' => $request->header('fcmToken'),
        ];

        // check for any error if occur
        if ($validation = $this->validateRegister ($validator, $validator1, $validator2, array_merge ($request->all(), $requestHeader))) {
            return ResponseTransformer::response (false, 'user', 'parameter errors', $validation->messages()->toArray(), 422);
        }

        // check for user registration
        if ($user = $manager->register ($request->only(['email', 'password', 'role']))) {
            // save user profile information belongs to user
            $manager->saveUserProfile ($user, $request->only (['firstName', 'lastName', 'mobile', 'countryId', 'address', 'postCode', 'image']));

            // check for device detail in header
            if ($request->hasHeader('deviceToken') && $request->hasHeader('deviceType') && $request->hasHeader('fcmToken')) {
                // save user device detail
                $manager->saveUserDeviceDetail ($user, $requestHeader);
            }

            // return success response
            return ResponseTransformer::response (true, 'user', 'user successfully registered', [], 201);
        }

        // returen unknown error
        return ResponseTransformer::response (false, 'user');
    }

    /**
     * Method to validate User registration fields
     * 
     * @param Register
     * @param Profile
     * @param Device
     * @param array
     * 
     * @return Validation
     */
    private function validateRegister (
        Register $validator, 
        Profile $validator1, 
        Device $validator2, 
        array $data
    ) {
        // merge Register and Profile Validation fields
        $validator->rules = array_merge ($validator->rules, $validator1->rules, $validator2->rules);

        // return validation response
        return $validator->validate ($data);
    }

    /**
     * Method to display Login user detail
     * 
     * @api users
     * @method  GET
     * 
     * @success-format: {"status":"success","title":"User","message":"User detail successfully found","data":{"id":1,"email":"emai@email.com","role":"customer","profile":{"firstName":"firstName","lastName":"lastName","mobile":"9632145870","address":"addressaddress","postCode":"postCode","mobile_verified":null,"email_verified":true,"name":"firstName lastName","avatar":"http:\/\/localhost:3000\/images\/avatar\/1"}}}
     * 
     * @return ResponseTransformer
     */
    public function detail (Request $request) {
        // fetch user detail from request
        return ResponseTransformer::response (true, 'User', 'User detail successfully found', $request->user()->with('profile')->first()->toArray());
    }

    /**
     * Method to update User Detail
     * 
     * @api users
     * @method  PUT
     * 
     * @table:  tbl_users
     * @table:  tbl_user_profile
     * 
     * @success-format: {"status":"success","title":"User","message":"User detail successfully updated","data":{}}
     * 
     * @return ResponseTransformer
     */
    public function update (ProfileUpdate $validator, Request $request) {

        // find user from request
        $user = $request->user()->first();

        // check for any error if occur
        if ($validation = $this->validateProfileFields ($validator, $request->all(), $user->id)) {
            return ResponseTransformer::response (false, 'user', 'parameter errors', $validation->messages()->toArray(), 422);
        }

        // update user information
        if ((new Manager)->updateUserProfile ($user, $request->only (['firstName', 'lastName', 'mobile', 'countryId', 'image', 'address', 'postCode']))) {
            return ResponseTransformer::response (true, 'User', 'User detail successfully updated');
        }

        return ResponseTransformer::response (false, 'User');
    }

    /**
     * Method to update and validate user profile update fields
     * 
     * @return validator
     */
    private function validateProfileFields (ProfileUpdate $validator, array $data, int $userId) {
        // append userId on mobile update
        $validator->rules['mobile'] .= ",{$userId},userId,deleted_at,NULL";

        // validate profile-update fields
        return $validator->validate ($data);
    }

    /**
     * Method to update password of login user
     * 
     * @api users/change-password
     * @method PUT
     * 
     * @success-format: {"status":"success","title":"user","message":"password successfully updated.","data":{}}
     * 
     * @table:  tbl_users
     * 
     * @return ResponseTransformer
     */
    public function changePassword (Password $validator, Request $request) {
        // validate change password fields
        $validator->rules['oldPassword'] = $validator->rules['password'];

        // check for any error if occur
        if ($validation = $validator->validate ($request->all())) {
            return ResponseTransformer::response (false, 'user', 'parameter errors', $validation->messages()->toArray(), 422);
        }

        // fetch user information
        $user = $request->user()->first();

        // verify user old password
        if (!app('hash')->check ($request->oldPassword, $user->password)) {
            return ResponseTransformer::response (false, 'user', 'Permission denied', ['Invalid old password'], 403);
        }

        // update user password
        if ((new Manager)->updateUser ($user, $request->only (['password']))) {
            return ResponseTransformer::response (true, 'user', 'password successfully updated.');
        }

        return ResponseTransformer::response (false, 'user');
    }
}