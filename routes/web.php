<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'users', 'namespace' => '\App\Modules\Users\Application\Controllers'], function () use ($router) {
    $router->post ('/', 'User@register');
    $router->post ('login', 'Auth@login');
    $router->post ('/forget-password', 'Account@forgetPassword');
    $router->put ('/forget-password/{token}', 'Account@resetPassword');

    $router->group(['middleware' => 'auth:api'], function () use ($router) {
        $router->delete ('logout', 'Auth@logout');
        $router->get ('/', 'User@detail');
        $router->put ('/', 'User@update');
        $router->put ('/change-password', 'User@changePassword');
    });
});

// routes for image
$router->group(['prefix' => 'image', 'namespace' => '\App\Modules\Images\Application\Controllers'], function () use ($router) {
    $router->post ('/', 'Image@store');
    $router->get ('/{imageType}/{imageId}', 'Image@get');
});