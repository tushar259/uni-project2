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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/home/uploadFiles', 'App\Http\Controllers\HomeController@uploadFiles');
Route::post('/usc/userLogin', 'App\Http\Controllers\UserCredentialController@userLogin');
Route::post('/usc/userReg', 'App\Http\Controllers\UserCredentialController@userRegistration');
Route::get('/home/ifUserLoggedIn', 'App\Http\Controllers\HomeController@checkIfUserLoggedIn');
Route::get('/usc/logout', 'App\Http\Controllers\UserCredentialController@logout');
Route::get('/home/getUploadedFiles', 'App\Http\Controllers\HomeController@getUploadedFiles');
Route::get('/home/getLoggedUploadedFiles', 'App\Http\Controllers\HomeController@getLoggedUploadedFiles');
Route::post('/home/enterReason', 'App\Http\Controllers\HomeController@enterReason');
Route::get('/home/getRequestedFiles', 'App\Http\Controllers\HomeController@getRequestedFiles');
Route::post('/home/updateRequestedFiles', 'App\Http\Controllers\HomeController@updateRequestedFiles');
Route::post('/home/fileDownloaded/{id}', 'App\Http\Controllers\HomeController@fileDownloaded');





