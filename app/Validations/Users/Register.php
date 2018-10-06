<?php

namespace App\Validations\Users;

use App\Validations\Validator;

class Register extends Validator {
    /**
	 * @var array Validation rules for the test form, they can contain in-built Laravel rules or our custom rules
	 */    
    public $rules = [
        'email' => 'bail|required|email|unique:tbl_users,email,NULL,id,deleted_at,NULL',
        'password' => 'bail|required|string|max:20',
        'role' => 'bail|nullable|string|max:20|in:customer,brewery',
        'token' => 'bail|nullable|string|max:50|exists:tbl_user_verification,token,deleted_at,NULL'
    ];

    /**
     * Set error messages
     * 
     * @var array
     */
    public $custom_errors = [];
}