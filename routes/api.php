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

    Route::get('ver','VerificationController@getAll');

    Route::prefix('ver')->group(function() {
      Route::post('add', 'VerificationController@add');
      Route::get('find/{id?}', 'VerificationController@findById');
      Route::get('{id?}', 'VerificationController@getByNum');
      Route::put('update/{id?}', 'VerificationController@update');
      Route::delete('delete/{id?}', 'VerificationController@delete');
    });

    Route::get('par', 'ParticipantsController@getAll');

    Route::prefix('par')->group(function() {
      Route::post('add', 'ParticipantsController@register');
      Route::get('find/{id?}', 'ParticipantsController@findById');
      Route::get('{id?}', 'ParticipantsController@getByNum');
      Route::put('update/{id?}', 'ParticipantsController@update');
      Route::delete('delete/{id?}', 'ParticipantsController@delete');
    });

    Route::get('com', 'CommitteesController@getAll');

    Route::prefix('com')->group(function() {
      Route::get('find/{id?}', 'CommitteesController@findById');
      Route::get('{id?}', 'CommitteesController@getByNum');
      Route::post('add', 'CommitteesController@register');
      Route::put('update/{id?}', 'CommitteesController@update');
      Route::delete('delete/{id?}', 'CommitteesController@delete');
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
