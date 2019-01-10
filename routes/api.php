<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('admin')->group(function() {

  Route::post('login','AdminController@login');

  Route::middleware('auth:admin')->group(function() {
    Route::get('detail','AdminController@detail');
    Route::get('logout','AdminController@logout');

    Route::get('verification','VerificationController@getAll');

    Route::prefix('verification')->group(function() {
      Route::post('add', 'VerificationController@add');
      Route::get('edit/{id?}', 'VerificationController@getByNum');
      Route::get('find/{id?}', 'VerificationController@findById');
      Route::post('update/{id?}', 'VerificationController@update');
      Route::get('delete/{id?}', 'VerificationController@delete');
    });

  });

});

Route::prefix('com')->group(function() {

  Route::post('register','CommitteesController@register');
  Route::post('login','CommitteesController@login');

  Route::middleware('auth:com')->group(function() {
    Route::get('detail','CommitteesController@detail');
    Route::get('logout','CommitteesController@logout');
  });

});

Route::prefix('par')->group(function() {

  Route::post('register','ParticipantsController@register');
  Route::post('login','ParticipantsController@login');

  Route::middleware('auth:par')->group(function() {
    Route::get('detail','ParticipantsController@detail');
    Route::get('logout','ParticipantsController@logout');
  });

});
