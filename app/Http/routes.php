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

$app->group(['prefix' => '/api/v1', 'middleware' => 'auth'], function () use ($app) {
// $app->group(['prefix' => '/api/v1'], function () use ($app) {

    // Users
    $app->get('/users/me', ['as' => 'userMe', 'uses' => 'UserController@me']);

    // Authentication
    $app->post('/auth/login', ['as' => 'auth', 'uses' => 'AuthController@login' ]);

    // Blog
    $app->get('/blogs', ['as' => 'blogs', 'uses' => 'BlogsController@index']);
    $app->post('/blogs', ['as' => 'blogCreate', 'uses' => 'BlogsController@create']);
    $app->get('/blogs/{blog:[0-9]+}', ['as' => 'blog', 'uses' => 'BlogsController@show']);
    $app->put('/blogs/{blog:[0-9]+}', ['as' => 'blogUpdate', 'uses' => 'BlogsController@update']);
    $app->get('/blogs/categories', ['as' => 'blogCategories', 'uses' => 'BlogCategoriesController@index']);
    $app->get('/blogs/categories/{blogCategoryID:[0-9]+}', ['as' => 'blogCategory', 'uses' => 'BlogCategoriesController@show']);
    $app->post('/blogs/categories', ['as' => 'blogCategoryCreate', 'uses' => 'BlogCategoriesController@create']);
    $app->put('/blogs/categories/{blogCategoryID:[0-9]+}', ['as' => 'blogCategoryUpdate', 'uses' => 'BlogCategoriesController@update']);
    $app->delete('/blogs/categories/{blogCategoryID:[0-9]+}', ['as' => 'blogCategoryDelete', 'uses' => 'BlogCategoriesController@delete']);

    // Pages
    $app->get('/pages', ['as' => 'pages', 'uses' => 'PagesController@index']);
    $app->get('/pages/tree', ['as' => 'pageTree', 'uses' => 'PagesController@tree']);
    $app->get('/pages/hierarchical', ['as' => 'pageTreeUpdate', 'uses' => 'PagesController@flatTree']);
    $app->post('/pages', ['as' => 'pageCreate', 'uses' => 'PagesController@create']);
    $app->get('/pages/{page:[0-9]+}', ['as' => 'page', 'uses' => 'PagesController@show']);
    $app->put('/pages/{page:[0-9]+}', ['as' => 'pageUpdate', 'uses' => 'PagesController@update']);
    $app->put('/pages/move/{page: [0-9]+}', ['as' => 'pageMove', 'uses' => 'pagesController@movePage']);

    // Products
    $app->get('/products', ['as' => 'products', 'uses' => 'ProductsController@index']);
    $app->post('/products', ['as' => 'productCreate', 'uses' => 'ProductsController@create']);
    $app->get('/products/{product:[0-9]+}', ['as' => 'product', 'uses' => 'ProductsController@show']);
    $app->put('/products/{product:[0-9]+}', ['as' => 'productUpdate', 'uses' => 'ProductsController@update']);
    $app->delete('/products/{product:[0-9]+}', ['as' => 'productDelete', 'uses' => 'ProductsController@delete']);

    $app->get('/products/tags', ['as' => 'productsTags', 'uses' => 'ProductTagsController@index']);
    $app->get('/products/tags/{productsTagID:[0-9]+}', ['as' => 'productsTag', 'uses' => 'ProductTagsController@show']);
    $app->post('/products/tags', ['as' => 'productsTagCreate', 'uses' => 'ProductTagsController@create']);
    $app->put('/products/tags/{productsTagID:[0-9]+}', ['as' => 'productsTagUpdate', 'uses' => 'ProductTagsController@update']);
    $app->delete('/products/tags/{productsTagID:[0-9]+}', ['as' => 'productsTagDelete', 'uses' => 'ProductTagsController@delete']);

    $app->post('/products/tags/assign/{productsTagID:[0-9]+}', ['as' => 'productsTagAssign', 'uses' => 'ProductTagsController@assign']);
    $app->delete('/products/tags/assign/{productsTagID:[0-9]+}', ['as' => 'productsTagRemove', 'uses' => 'ProductTagsController@remove']);

    // Settings - Modules
    $app->get('/settings/modules', ['as' => 'modules', 'uses' => 'ModulesController@index']);
    $app->put('/settings/modules/{module: [0-9]+}', ['as' => 'moduleUpdate', 'uses' => 'ModulesController@update']);
    $app->get('/settings/modules/module/{moduleName}', ['as' => 'moduleByName', 'uses' => 'ModulesController@showByName']);

});
