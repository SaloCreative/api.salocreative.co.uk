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

    // Blog
    $app->get('/blogs', ['as' => 'blogs', 'uses' => 'BlogsController@index']);
    $app->post('/blogs', ['as' => 'blogCreate', 'uses' => 'BlogsController@create']);
    $app->get('/blogs/{blog:[0-9]+}', ['as' => 'page', 'uses' => 'BlogsController@show']);
    $app->put('/blogs/{blog:[0-9]+}', ['as' => 'blogUpdate', 'uses' => 'BlogsController@update']);
    $app->get('/blogs/categories', ['as' => 'blogCategories', 'uses' => 'BlogCategoriesController@index']);
    $app->post('/blogs/categories', ['as' => 'blogCategoryCreate', 'uses' => 'BlogCategoriesController@create']);

});
