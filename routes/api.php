<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['namespace' => 'App\Http\Controllers\Api\v1', 'prefix' => 'v1'], function () {
    Route::get('/', function () {
        return response()->json(['success' => true]);
    });
    // Display a list of all registered users.
    Route::get('/users', 'UserController@index')->name('users.index');
    // Display a list of all registered users with the details.
    Route::get('/users/details', 'UserController@details')->name('users.details');
    // Display the details of the user with a specific User ID.
    Route::get('/users/{userId}', 'UserController@index')->name('user.index');
    // Display the details of the user with a specific User ID with the details.
    Route::get('/users/{userId}/details', 'UserController@details')->name('user.details');
    // Display a list of users who are being followed by the user with a specific User ID.
    Route::get('/users/{userId}/following', 'UserController@showFollowing')->name('user.following');
    // Display a list of users who are following the user with a specific User ID and Name.
    Route::get('/users/{userId}/followers/{followerName?}', 'UserController@showFollowers')->name('user.follower_by_name');

    //Route::post('/follow/{userId}/{userFollowId}', 'FollowController@index');

    // Requests for following a user.
    Route::post('/follow', 'FollowController@index')->name('follow.follow');
    // Unfollowing a user from followers side.
    Route::delete('/unfollow/{userId}/{followingUserId}', 'FollowController@deleteFollowing')->name('follow.unfollow');
    // Unfollowing a user from user side.
    Route::delete('/follower/{userId}/{followingUserId}', 'FollowController@deleteFollower')->name('follower.delete');
});

/*
Route::prefix('v1')->namespace('App\Http\Controllers\Api')->group(function () {
    Route::get('/users', 'v1\UserController@index');
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/
