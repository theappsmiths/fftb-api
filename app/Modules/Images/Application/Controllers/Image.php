<?php

namespace App\Modules\Images\Application\Controllers;

use App\Http\Controllers\Controller;
use App\Transformers\ResponseTransformer;
use Illuminate\Http\Request;

use App\Modules\Images\Domain\Manager;

use App\Validations\Image\Image as ImageValidation;

class Image extends Controller {

    protected $uploadPaths = [];


    /**
     * Method to store images
     * 
     * @dir <App\Storage\app>
     * 
     * @api image   [POST]
     * 
     * @access any
     * 
     * @success-format: {"status":"success","title":"Image","message":"Image successfully uploaded.","data":["avatar\/8e588e44e6b9ebc0a6b538b42aee7bdd.png"]}
     * 
     * @return ResponseTransformer HTTP
     */
    public function store (ImageValidation $validator, Request $request) {
        $validate_request = $validator->validate( $request->all() );

        // check if any error exists
        if (gettype ($validate_request) === "object") {
            return ResponseTransformer::response (false, 'Image', 'Parameter Errors', $validate_request->messages()->toArray(), 422);
        }
        
        if ($request->hasFile('image')) {
            collect ($request->image)->each (function ($image) use ($request) {
                $file_name = md5 (microtime (true)).'.'.$image->getClientOriginalExtension();
                // save image into storage directory
                if ($image->move(storage_path('app').'/'.$request->directory, $file_name)) {
                    $this->upload_paths[] = $request->directory . '/' . $file_name;
                }
            });

            // check if files successfully saved
            if (count ($this->upload_paths)) {
                // send path and status of all uploaded files
                return ResponseTransformer::response (true, 'Image', 'Image successfully uploaded.',$this->upload_paths, 201);
            }
        }

        return ResponseTransformer::response (false, 'Image');
    }

    /**
     * Method to get image
     * 
     * @api image/{imageType}/{imageId}   
     * @method  GET
     * 
     * @access any
     * 
     * @return file
     */
    public function get (string $imageType, int $imageId) {
        // fetch image by type and id
        return response((new Manager)->get ($imageType, $imageId), 200)->header('Content-Type', 'image/*');
    }
}