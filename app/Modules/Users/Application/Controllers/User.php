<?php

namespace App\Modules\Users\Application\Controllers;

use App\Http\Controllers\Controller;
use App\Transformers\ResponseTransformer;
use Illuminate\Http\Request;

use App\Validations\Users\Register;
use App\Validations\Users\Profile;
use App\Validations\Users\Device;

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

}