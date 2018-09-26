<?php

namespace App\Modules\Users\Application\Controllers;

use App\Http\Controllers\Controller;
use App\Transformers\ResponseTransformer;
use App\Transformers\PaginationTransformer;
use Illuminate\Http\Request;

use App\Validations\Common\Paginate;

use App\Modules\Users\Domain\Manager;

class Admin extends Controller {

    /**
     * Method to fetch list of all users
     * 
     * @api users/list
     * @method  GET
     * 
     * @access admin
     * 
     * @success-format: {"status":"success","title":"User","message":"User list successfully found","data":{"current_page":1,"data":[{"id":2,"email":"email@email.com","role":"customer","profile":{"firstName":"firstName","lastName":"lastName","mobile":"9632145871","address":"addressaddress","postCode":"postCode","mobile_verified":null,"email_verified":true,"name":"firstName lastName","avatar":"http:\/\/localhost:3000\/image\/avatar\/2"}}],"first_page_url":"http:\/\/localhost:3000\/users\/list?page=1","from":1,"last_page":2,"last_page_url":"http:\/\/localhost:3000\/users\/list?page=2","next_page_url":"http:\/\/localhost:3000\/users\/list?page=2","path":"http:\/\/localhost:3000\/users\/list","per_page":1,"prev_page_url":null,"to":1,"total":2}}
     * 
     * @return ResponseTransformer
     */
    public function getUsers (Paginate $validator, Request $request) {
        // check for validation
        if ($validation = $validator->validate ($request->all())) {
            return ResponseTransformer::response (false, 'user', 'parameter errors', $validation->messages()->toArray(), 422);
        }

        // fetch list of all users
        return ResponseTransformer::response (true, 'User', 'User list successfully found', 
            PaginationTransformer::paginate (
                $request->user()->orderBy('created_at', 'desc')->with('profile'),
                $request->input('paginate', null)
            )->toArray());
    }
}