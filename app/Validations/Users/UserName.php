<?php

namespace App\Validations\Users;

use App\Validations\Validator;

class UserName extends Validator {
    /**
	 * @var array Validation rules for the test form, they can contain in-built Laravel rules or our custom rules
	 */    
    public $rules = [
        'username' => 'bail|required|email|exists:tbl_users,email,deleted_at,NULL'
    ];

    /**
     * Set error messages
     * 
     * @var array
     */
    public $custom_errors = [
        'in' => 'The :attribute can have following values: :values'
    ];
}