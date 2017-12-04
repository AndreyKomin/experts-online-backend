<?php

use App\Http\Controllers\Api\v1\UsersController;
use Dingo\Api\Routing\Router;
use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\MessengersController;

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
/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->get('/users', UsersController::class . '@index');
    $api->post('/auth', AuthController::class . '@authenticate');
    $api->get('/messengers', MessengersController::class . '@index');
    $api->get('/search', UsersController::class . '@search');
    $api->group(['middleware' => 'api.auth'], function (Router $api) {
        $api->delete('/auth', AuthController::class . '@logout');
        $api->put('/auth', AuthController::class . '@refreshToken');

        $api->put('/me', AuthController::class . '@update');
        $api->get('/me', AuthController::class . '@me');
    });
});
