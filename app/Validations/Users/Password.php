<?php

namespace App\Validations\Users;

use App\Validations\Validator;

class Password extends Validator {
    /**
	 * @var array Validation rules for the test form, they can contain in-built Laravel rules or our custom rules
	 */    
    public $rules = [
        'password' => 'bail|required|string|max:20'
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