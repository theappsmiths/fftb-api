<?php

namespace App\Modules\Users\Infrastructure\Repositories;

use App\Models\User\Verification as VerificationModel;

class Verification {

    protected $model;

    public function __construct () {
        $this->model = (new VerificationModel);
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
        return $this->model->type($type)->token ($token);
    }
}