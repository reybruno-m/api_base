<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FunctionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FunctionProfileController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\Mail\UserEmailController;

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

Route::get('/', function () { return "API REST"; });
Route::get('status', function () { return true; });

Route::post('testMail', [UserEmailController::class, 'createAccount'])->name('createAccount');

# Auth
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    # Auth
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('signup', [AuthController::class, 'signup'])->name('signup');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('me', [AuthController::class, 'me'])->name('me');
    # Validación de cuenta.
    Route::get('validate/{uuid}', [AuthController::class, 'validateAccount'])->name('validateAccount');
    # Recuperación de clave.
    Route::post('password/forgotten', [AuthController::class, 'forgottenPwd'])->name('forgottenPwd');
    Route::post('password/update/{uuid}', [AuthController::class, 'updatePwd'])->name('updatePwd');
});

Route::middleware(['api'])->group(function () {
    Route::apiResources([
        'users' => UserController::class,
        'function' => FunctionController::class,
        'profile' => ProfileController::class,
        'functionProfile' => FunctionProfileController::class,
    ]);
});