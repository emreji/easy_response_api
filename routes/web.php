<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('events', 'EventsController@index');
Route::post('events', 'EventsController@store');
Route::get('events/{id}', 'EventsController@show');
Route::put('events/{id}', 'EventsController@update');
Route::delete('events/{id}', 'EventsController@destroy');

Route::get('attendees', 'AttendeesController@index');
Route::post('attendees', 'AttendeesController@store');
Route::get('attendees/{id}', 'AttendeesController@show');
Route::get('attendees/event/{eventId}', 'AttendeesController@getAttendeesForEventId');
Route::put('attendees/{id}', 'AttendeesController@update');
Route::delete('attendees/{id}', 'AttendeesController@destroy');

Route::post('sessions', 'SessionController@store');

Route::get('logout', 'Auth\LoginController@logout');