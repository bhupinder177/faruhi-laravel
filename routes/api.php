<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Headers: Accept, Authorization');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE , Accept-Enconding-Authorization');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    //Route::get('/getuser', [ApiController::class, 'getuser']);

});

Route::group(['middleware' => ['cors']], function () {

Route::post('/register', [ApiController::class, 'register']);
Route::post('/login', [ApiController::class, 'login']);
Route::post('/forget-password', [ApiController::class, 'forgetPassword']);
Route::get('/getcountries', [ApiController::class, 'allCountry']);
Route::get('/getstates/{country_id}', [ApiController::class, 'getStates']);
Route::get('/getcities/{state_id}', [ApiController::class, 'getCity']);

Route::group([
  'middleware' => 'auth:sanctum'
], function() {

Route::get('/getuser', [ApiController::class, 'getuser']);
Route::get('/logout', [ApiController::class, 'logout']);
Route::post('/change-password', [ApiController::class, 'changePassword']);
Route::post('/update-profile', [ApiController::class, 'updateProfile']);
Route::get('/getcompanies', [ApiController::class, 'getCompanies']);

});

});
