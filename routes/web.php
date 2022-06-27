<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

use App\Models\User;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->post('/register', 'ApiController@registerUser');
        $router->post('/sign-in', 'ApiController@authUser');
        $router->post('/recover-password', 'ApiController@recoverUserPassword');

        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->get('/companies', 'ApiController@getUserCompanies');
            $router->post('/companies', 'ApiController@createUserCompany');
        });
    });
});
