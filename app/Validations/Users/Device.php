<?php

namespace App\Validations\Users;

use App\Validations\Validator;

class Device extends Validator {
    /**
	 * @var array Validation rules for the test form, they can contain in-built Laravel rules or our custom rules
	 */    
    public $rules = [
        'deviceToken' => 'bail|nullable|string|max:255',
        'deviceType' => 'bail|nullable|string|max:255',
        'fcmToken' => 'bail|nullable|string|max:255'
    ];

    /**
     * Set error messages
     * 
     * @var array
     */
    public $custom_errors = [];
}