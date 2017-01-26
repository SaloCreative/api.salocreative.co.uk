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

$app->get('/', function () use ($app) {
    return $app->version();
});

//$app->group(['prefix' => '/api/v1', 'middleware' => 'auth'], function () use ($app) {
$app->group(['prefix' => '/api/v1'], function () use ($app) {

    // Users
    $app->get('/users/me', ['as' => 'userMe', 'uses' => 'UserController@me']);

    // Authentication
    $app->post('/auth/login', ['as' => 'auth', 'uses' => 'AuthController@login' ]);

    // Pages
    $app->get('/pages', ['as' => 'pages', 'uses' => 'PagesController@index']);
    $app->get('/pages/tree', ['as' => 'pageTree', 'uses' => 'PagesController@tree']);
    $app->get('/pages/hierarchical', ['as' => 'pageTreeUpdate', 'uses' => 'PagesController@flatTree']);

    $app->post('/pages', ['as' => 'pageCreate', 'uses' => 'PagesController@create']);

    $app->get('/pages/{page:[0-9]+}', ['as' => 'page', 'uses' => 'PagesController@show']);
    $app->put('/pages/{page:[0-9]+}', ['as' => 'pageUpdate', 'uses' => 'PagesController@update']);
    $app->put('/pages/move/{page: [0-9]+}', ['as' => 'pageMove', 'uses' => 'pagesController@movePage']);

    $app->get('/pages/testing', ['as' => 'test', 'uses' => 'pagesController@testPage']);

});
