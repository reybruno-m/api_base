<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FunctionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FunctionProfileController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\Mail\UserEmailController;

use App\Http\Controllers\LocationController;
use App\Http\Controllers\IVAController;
use App\Http\Controllers\PaidMethodsController;

#--------------------------------------------------------------------------
# API TEST Routes
#--------------------------------------------------------------------------

Route::post('testMail', [UserEmailController::class, 'createAccount']);

#--------------------------------------------------------------------------
# API Prod Routes
#--------------------------------------------------------------------------

Route::get('/', function () { return "API REST"; });
Route::get('status', function () { return true; });

# Auth
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    # Auth
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    # Validación de cuenta.
    Route::get('validate/{uuid}', [AuthController::class, 'validateEmail']);
    # Recuperación de clave.
    Route::post('password/forgotten', [AuthController::class, 'forgottenPwd']);
    Route::post('password/update/{uuid}', [AuthController::class, 'updatePwd']);
});

Route::middleware(['api'])->group(function () {

    # CRUDS completos de API
    Route::apiResources([
        'users' => UserController::class,
        'function' => FunctionController::class,
        'profile' => ProfileController::class,
    ]);

    # CRUDS parciales

    Route::resource('functionProfile', FunctionProfileController::class)->only([
        'store', 'destroy'
    ]);
});

# Catalogs
Route::group([
    'middleware' => 'api',
    'prefix' => 'catalogs'
], function ($router) {

    Route::get('locations', [LocationController::class, 'locations']);
    Route::get('locations/{term}', [LocationController::class, 'locationsAutocomplete']);

    Route::get('iva', [IVAController::class, 'index']);
    Route::get('paid-methods', [PaidMethodsController::class, 'index']);
});