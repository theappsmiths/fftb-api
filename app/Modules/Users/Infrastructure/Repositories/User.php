<?php

namespace App\Modules\Users\Infrastructure\Repositories;

use App\Modules\Users\Infrastructure\Models\User as UserModel;

class User {
    
    protected $model;

    /**
     * Set User model available in whole repository
     */
    public function __construct () {
        $this->model = (new UserModel);
    }

    public function create (array $data) {
        return $this->model->create($data);
    }

    public function findUserByAttr (string $attrName, string $attrVal) {
        return $this->model->where($attrName, $attrVal);
    }
}