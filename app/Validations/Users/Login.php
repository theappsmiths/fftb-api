<?php

namespace App\Validations\Users;

use App\Validations\Validator;

class Login extends Validator {
    /**
	 * @var array Validation rules for the test form, they can contain in-built Laravel rules or our custom rules
	 */    
    public $rules = [
        'password' => 'bail|required|string|max:20',
        'role' => 'bail|nullable|string|max:20|in:customer,brewery,admin',

        'grant_type' => 'bail|required|string|in:password',
        'client_id' => 'bail|required|numeric|exists:oauth_clients,id',
        'client_secret' => 'bail|required|exists:oauth_clients,secret,password_client,1'
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