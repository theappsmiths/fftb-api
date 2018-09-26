<?php

namespace App\Validations\Image;

use App\Validations\Validator;

class Image extends Validator {
    /**
	 * @var array Validation rules for the test form, they can contain in-built Laravel rules or our custom rules
	 */    
    public $rules = [
        'image' => 'bail|required|array|min:1',
        'image.*' => 'bail|required|image|mimes:jpg,jpeg,png|min:1|max:10240',
        'directory' => 'bail|required|in:avatar'
    ];

    /**
     * Set error messages
     * 
     * @var array
     */
    public $custom_errors = [
        'in' => 'The :attribute must be one of the value: :values'
    ];
}