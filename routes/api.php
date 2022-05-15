<?php

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

// more proper route will be: POST /user/:to_user_id/reactions (but task assumes that all parameters will be sent in request body)
Route::post('/reactions', \App\Http\Controllers\API\REST\AddReactionToUser::class);
