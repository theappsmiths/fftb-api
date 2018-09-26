<?php

namespace App\Validations\Common;

use App\Validations\Validator;

class Paginate extends Validator {
    /**
	 * @var array Validation rules for the test form, they can contain in-built Laravel rules or our custom rules
	 */    
    public $rules = [
        'paginate' => 'bail|nullable|numeric'
    ];

    /**
     * Set error messages
     * 
     * @var array
     */
    public $custom_errors = [];
}