<?php

namespace App\Modules\Users\Domain;

use App\Modules\Users\Infrastructure\Repositories\User;
use App\Modules\Users\Infrastructure\Repositories\Verification;

use App\Modules\Users\Domain\Entities\Device;

class Manager {

    protected $user;

    public function __construct () { }

    /**
     * Method to Register User
     * 
     * @param array
     * 
     * @return collection
     */
    public function register (array $data) {
        return (new User)->create($data);
    }

    /**
     * Method to save device detail of the user
     * 
     * @param User
     * @param array
     * 
     * @return collection
     */
    public function saveUserDeviceDetail ($user, array $deviceDetail) {
        $device = new Device ($deviceDetail['deviceToken'], $deviceDetail['deviceType'], $deviceDetail['fcmToken']);

        // save device information of the user device
        return $user->device()->UpdateOrCreate(['deviceDetail' => json_encode ($device->toArray())], ['deviceDetail' => $device->toArray()]);
    }

    /**
     * Method to save or update user profile information
     * 
     * @param User
     * @param array
     * 
     * @return collection
     */
    public function saveUserProfile ($user, array $profile) {
        return $user->profile()->updateOrCreate([], $profile);
    }

    /**
     * Method to find User by Email
     * 
     * @param string email
     * 
     * @return collection
     */
    public function findUserByEmail (string $email) {
        return (new User)->findUserByAttr ('email', $email)->first();
    }

    /**
     * method to find User by verification method
     * 
     * @param  string verification-type
     * @param verification token
     * 
     * @return collection
     */
    public function findUserByVerification (string $type, string $token) {
        return (new Verification)->findUserByVerification ($type, $token)->first();
    }

    /**
     * Method to update user detail
     * 
     * @param   collection $user
     * @param array $data
     * 
     * @return boolean
     */
    public function updateUser ($user, array $data) {
        return $user->update ($data);
    }

    /**
     * method to verify User Email
     * 
     * @param collection $user
     * 
     * @return boolean
     */
    public function updateUserProfile ($user, array $data) {
        $user->profile()->update ($data);
    }

    /**
     * Method to find user by attribute
     */
    public function findUserByAttr (string $attrName, string $attrVal) {
        return (new User)->findUserByAttr ($attrName, $attrVal)->first();
    }
}