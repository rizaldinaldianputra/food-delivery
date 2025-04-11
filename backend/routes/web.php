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

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');     // Create user
    $router->post('login', 'AuthController@login');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('profile', 'UserController@profile'); // Lihat profil user
        $router->post('logout', 'AuthController@logout');

        $router->group(['prefix' => 'products'], function () use ($router) {
            $router->get('/', 'ProductController@index');       // Get all products
            $router->get('/{id}', 'ProductController@show');   // Get single product
            $router->post('/', 'ProductController@store');     // Create product
            $router->put('/{id}', 'ProductController@update'); // Update product
            $router->delete('/{id}', 'ProductController@destroy'); // Delete product
        });

        $router->group(['prefix' => 'categories'], function () use ($router) {
            $router->get('/', 'CategoryController@index');       // Get all categories
            $router->get('/{id}', 'CategoryController@show');   // Get single category
            $router->post('/', 'CategoryController@store');     // Create category
            $router->put('/{id}', 'CategoryController@update'); // Update category
            $router->delete('/{id}', 'CategoryController@destroy'); // Delete category
        });

        $router->group(['prefix' => 'cart'], function () use ($router) {
            $router->get('/', 'CartController@index');       // Get all cart items
            $router->post('/', 'CartController@store');     // Add product to cart
            $router->put('/{id}', 'CartController@update'); // Update cart item
            $router->delete('/{id}', 'CartController@destroy'); // Remove product from cart
        });

        $router->group(['prefix' => 'wishlist'], function () use ($router) {
            $router->get('/', 'WishlistController@index');       // Get all wishlist items
            $router->post('/', 'WishlistController@store');     // Add product to wishlist
            $router->delete('/{id}', 'WishlistController@destroy'); // Remove product from wishlist
        });

        $router->group(['prefix' => 'users'], function () use ($router) {
            $router->get('/', 'UserController@index');       // Get all users
            $router->get('/{id}', 'UserController@show');   // Get single user
            $router->put('/{id}', 'UserController@update'); // Update user
            $router->delete('/{id}', 'UserController@destroy'); // Delete user
        });

        $router->group(['prefix' => 'roles'], function () use ($router) {
            $router->get('/', 'RoleController@index');       // Get all roles
            $router->get('/{id}', 'RoleController@show');   // Get single role
            $router->post('/', 'RoleController@store');     // Create role
            $router->put('/{id}', 'RoleController@update'); // Update role
            $router->delete('/{id}', 'RoleController@destroy'); // Delete role
        });
    });
});