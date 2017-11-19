<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\v1\UsersController;
use Dingo\Api\Routing\Router;
use App\Http\Controllers\Api\v1\Auth\LoginController;
use App\Http\Controllers\Api\v1\BotController;

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
    $api->post('/auth', LoginController::class . '@authenticate');

    $api->group([], function (Router $api) {
        $api->post('/decision', BotController::class . '@decision');
        $api->post('/register', BotController::class . '@register');
    });


    $api->group(['middleware' => 'api.auth'], function (Router $api) {
        $api->delete('/auth', LoginController::class . '@logout');
        $api->put('/auth', LoginController::class . '@refreshToken');



        $api->put('/users/{user}', UsersController::class . '@update');
    });
});
