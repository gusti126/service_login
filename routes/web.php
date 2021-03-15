<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use FastRoute\Route;

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

//user
$router->get('/user', 'UserController@index');
$router->get('/user/{id}', 'UserController@show');
$router->put('/user/edit/{id}', 'UserController@update');

// profile
$router->get('/profile', 'ProfileController@index');
$router->post('/profile/update/{id}', 'ProfileController@update');

$router->post("/register", "AuthController@register");
$router->post("/login", "AuthController@login");
// $router->get('/user', 'AuthController@detail')->middleware('auth');
$router->group(['middleware' => 'auth'], function () use ($router) {
        //  $router->get('/profile', 'AuthController@details');
        $router->get('/profile/{id}', 'ProfileController@show');
        $router->post('/logout', 'AuthController@logout');
});
$router->post('/mentor/create', 'mentorController@create');
$router->get('/mentor', 'mentorController@getMentor');
$router->get('/mentor/{id}', 'mentorController@show');

// course
$router->get('/course', 'CourseController@index');
$router->get('/course/{id}', 'CourseController@show');
$router->get('/kategori', 'KategoriController@index');
$router->get('/kategori/{id}', 'KategoriController@show');

// Chapter
$router->get('/chapter', 'ChapterController@index');
$router->get('/chapter/{id}', 'ChapterController@show');
// lesson
$router->get('/lesson', 'LessonController@index');
$router->get('/lesson/{id}', 'LessonController@show');

// referal dan user
$router->post('/referal-cari', 'AuthController@cariReferal');
// point
$router->post('/create-point', 'AuthController@createPoint');
$router->post('/kurang-point', 'ProfileController@kurangPoint');
