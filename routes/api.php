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

// Admin endpoint
Route::prefix('admin')->group(function() {

  // Admin Login endpoint
  Route::post('login','AdminController@login');

  Route::middleware('auth:admin')->group(function() {

    Route::get('detail','AdminController@detail');
    Route::get('logout','AdminController@logout');

    // Admin Verification endpoint
    Route::get('ver','VerificationController@getAll');
    Route::post('ver', 'VerificationController@add');

    Route::prefix('ver')->group(function() {
      Route::get('find/{id?}', 'VerificationController@findById');
      Route::get('{id?}', 'VerificationController@getByNum');
      Route::put('update/{id?}', 'VerificationController@update');
      Route::delete('{id}', 'VerificationController@delete');
    });

    // Admin Participants endpoint
    Route::get('par', 'ParticipantsController@getAll');
    Route::post('par', 'ParticipantsController@register');

    Route::prefix('par')->group(function() {
      Route::get('find/{id?}', 'ParticipantsController@findById');
      Route::get('{id?}', 'ParticipantsController@getByNum');
      Route::put('update/{id?}', 'ParticipantsController@update');
      Route::delete('{id?}', 'ParticipantsController@delete');
    });

    // Admin committees endpoint
    Route::get('com', 'CommitteesController@getAll');
    Route::post('com', 'CommitteesController@register');

    Route::prefix('com')->group(function() {
      Route::get('find/{id?}', 'CommitteesController@findById');
      Route::get('{id?}', 'CommitteesController@getByNum');
      Route::put('update/{id?}', 'CommitteesController@update');
      Route::delete('{id?}', 'CommitteesController@delete');
    });

  });

});

// committees endpoint
Route::prefix('com')->group(function() {

  // login and register committees
  Route::post('register','CommitteesController@register');
  Route::post('login','CommitteesController@login');

  // Committees endpoint
  Route::middleware('auth:com')->group(function() {

    Route::get('detail','CommitteesController@detail');
    Route::get('logout','CommitteesController@logout');

    // Comittees Tests endpoint
    Route::get('tests', 'TestsController@index');
    Route::post('tests', 'TestsController@store');

    Route::prefix('tests')->group(function() {

      // Committees Test end point
      Route::get('{id}', 'TestsController@show');
      Route::put('{id}/update', 'TestsController@update');
      Route::delete('{id}', 'TestsController@destroy');

      // Committees Question end point
      Route::prefix('{id}')->group(function() {
        Route::post('questions', 'QuestionController@store');
        Route::put('questions/{qid}', 'QuestionController@update');
        Route::delete('questions/{qid}', 'QuestionController@destroy');
        Route::get('questions', 'QuestionController@index');
        Route::get('questions/{qid}', 'QuestionController@show');
      });

    });

  });

});

// Participants endpoint
Route::prefix('par')->group(function() {

  // Participants login and register endpoint
  Route::post('register','ParticipantsController@register');
  Route::post('login','ParticipantsController@login');

  // Participants detail and logout endpoint
  Route::middleware('auth:par')->group(function() {
    Route::get('detail','ParticipantsController@detail');
    Route::get('logout','ParticipantsController@logout');
  });

});
