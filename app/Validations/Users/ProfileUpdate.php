<?php

namespace App\Validations\Users;

use App\Validations\Validator;

class ProfileUpdate extends Validator {
    /**
	 * @var array Validation rules for the test form, they can contain in-built Laravel rules or our custom rules
	 */    
    public $rules = [
        'firstName' => 'bail|nullable|string|max:100',
        'lastName' => 'bail|nullable|string|max:100',
        'mobile' => 'bail|nullable|digits_between:10,15|unique:tbl_user_profile,mobile',
        'countryId' => 'bail|nullable|numeric|exists:tbl_countries,id,deleted_at,NULL',
        'image' => 'bail|nullable|string|max:255',
        'address' => 'bail|nullable|string|max:255',
        'postCode' => 'bail|nullable|string|max:8'
    ];

    /**
     * Set error messages
     * 
     * @var array
     */
    public $custom_errors = [];
}