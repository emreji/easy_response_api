<?php

use Illuminate\Http\Request;
use App\Event;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('events', 'EventsController@getEventsForUser');
Route::post('events', 'EventsController@store');
Route::get('events/{id}', 'EventsController@show');
Route::put('events/{id}', 'EventsController@update');
Route::delete('events/{id}', 'EventsController@destroy');

Route::get('attendees', 'AttendeesController@index');
Route::post('attendees', 'AttendeesController@store');
//Route::get('attendees/{id}', 'AttendeesController@show');
Route::get('attendees/event/{eventId}', 'AttendeesController@getAttendeesForEventId');
Route::put('attendees/{id}', 'AttendeesController@update');

Route::get('session/{id}', 'SessionController@userDoesExistForSessionId');

Route::get('user', 'Auth\LoginController@getUserBySessionId');
Route::get('logout', 'Auth\LoginController@logout');

Route::get('remind/event/{eventId}', 'AttendeesController@sendReminderEmail');