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

    // Media
    $app->get('/media', ['as' => 'media', 'uses' => 'MediaController@index']);
    $app->post('/media', ['as' => 'mediaUplaod', 'uses' => 'MediaController@create']);
    $app->get('/media/{media:[0-9]+}', ['as' => 'media', 'uses' => 'MediaController@show']);
    $app->put('/media/{media:[0-9]+}', ['as' => 'mediaUpdate', 'uses' => 'MediaController@update']);
    $app->delete('/media/{media:[0-9]+}', ['as' => 'mediaDelete', 'uses' => 'MediaController@delete']);

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

    $app->get('/products/categories', ['as' => 'productCategories', 'uses' => 'ProductCategoriesController@index']);
    $app->get('/products/categories/tree', ['as' => 'productCategoriesTree', 'uses' => 'ProductCategoriesController@tree']);
    $app->get('/products/categories/{productsCategoryID:[0-9]+}', ['as' => 'productsCategory', 'uses' => 'ProductCategoriesController@show']);
    $app->post('/products/categories', ['as' => 'productsCategoryCreate', 'uses' => 'ProductCategoriesController@create']);
    $app->put('/products/categories/{productsCategoryID:[0-9]+}', ['as' => 'productsCategoryUpdate', 'uses' => 'ProductCategoriesController@update']);
    $app->delete('/products/categories/{productsCategoryID:[0-9]+}', ['as' => 'productsCategoryDelete', 'uses' => 'ProductCategoriesController@delete']);

    $app->post('/products/dimensions', ['as' => 'dimensionManage', 'uses' => 'DimensionsController@manage']);
    $app->post('/products/dimensions/add/{productID:[0-9]+}', ['as' => 'dimensionsAdd', 'uses' => 'DimensionsController@bulkAdd']);

    $app->get('/products/dimensions/fields', ['as' => 'dimensionFields', 'uses' => 'DimensionFieldsController@index']);
    $app->get('/products/dimensions/fields/{dimensionFieldsID:[0-9]+}', ['as' => 'dimensionField', 'uses' => 'DimensionFieldsController@show']);
    $app->post('/products/dimensions/fields', ['as' => 'dimensionFieldCreate', 'uses' => 'DimensionFieldsController@create']);
    $app->put('/products/dimensions/fields/{dimensionFieldID:[0-9]+}', ['as' => 'dimensionFieldUpdate', 'uses' => 'DimensionFieldsController@update']);
    $app->delete('/products/dimensions/fields/{dimensionFieldID:[0-9]+}', ['as' => 'dimensionFieldDelete', 'uses' => 'DimensionFieldsController@delete']);

    $app->post('/products/dimensions/fields/assign/{dimensionFieldID:[0-9]+}', ['as' => 'dimensionFieldAssign', 'uses' => 'DimensionFieldsController@assign']);
    $app->put('/products/dimensions/fields/assign/{dimensionFieldID:[0-9]+}', ['as' => 'dimensionFieldRemove', 'uses' => 'DimensionFieldsController@remove']);

    $app->get('/products/gallery/{productID:[0-9]+}', ['as' => 'productImages', 'uses' => 'ProductGalleryController@gallery']);
    $app->post('/products/gallery/{productID:[0-9]+}', ['as' => 'productImageAdd', 'uses' => 'ProductGalleryController@addImage']);
    $app->put('/products/gallery/{productID:[0-9]+}', ['as' => 'productImageRemoves', 'uses' => 'ProductGalleryController@removeImage']);
    $app->post('/products/gallery/multiple/{productID:[0-9]+}', ['as' => 'productImagesAdd', 'uses' => 'ProductGalleryController@addImages']);
    $app->post('/products/gallery/order/{productID:[0-9]+}', ['as' => 'productImagesOrder', 'uses' => 'ProductGalleryController@orderImages']);

    $app->get('/products/tags', ['as' => 'productsTags', 'uses' => 'ProductTagsController@index']);
    $app->get('/products/tags/{productsTagID:[0-9]+}', ['as' => 'productsTag', 'uses' => 'ProductTagsController@show']);
    $app->post('/products/tags', ['as' => 'productsTagCreate', 'uses' => 'ProductTagsController@create']);
    $app->put('/products/tags/{productsTagID:[0-9]+}', ['as' => 'productsTagUpdate', 'uses' => 'ProductTagsController@update']);
    $app->delete('/products/tags/{productsTagID:[0-9]+}', ['as' => 'productsTagDelete', 'uses' => 'ProductTagsController@delete']);

    $app->post('/products/tags/assign/{productsTagID:[0-9]+}', ['as' => 'productsTagAssign', 'uses' => 'ProductTagsController@assign']);
    $app->post('/products/tags/add/{productID:[0-9]+}', ['as' => 'productsTagBulkAssign', 'uses' => 'ProductTagsController@bulkAdd']);
    $app->delete('/products/tags/assign/{productsTagID:[0-9]+}', ['as' => 'productsTagRemove', 'uses' => 'ProductTagsController@remove']);

    // Settings - Modules
    $app->get('/settings/modules', ['as' => 'modules', 'uses' => 'ModulesController@index']);
    $app->put('/settings/modules/{module: [0-9]+}', ['as' => 'moduleUpdate', 'uses' => 'ModulesController@update']);
    $app->get('/settings/modules/module/{moduleName}', ['as' => 'moduleByName', 'uses' => 'ModulesController@showByName']);

});
