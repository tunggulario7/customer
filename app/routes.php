<?php
declare(strict_types=1);

use App\Models\Connection as DBConnetcion;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world guys!');
        return $response;
    });

    $app->group('/customer', function (Group $group) {
        $group->get('', 'App\Controllers\CustomerController::getAll')->setName('get-data');
        $group->post('', 'App\Controllers\CustomerController::insert')->setName('insert-data');
        $group->put('/[{id}]', 'App\Controllers\CustomerController::update')->setName('update-data');
        $group->delete('/[{id}]', 'App\Controllers\CustomerController::delete')->setName('delete-data');
    });

    $app->group('/loan/purpose', function (Group $group) {
        $group->get('', 'App\Controllers\LoanPurposeController::getAll')->setName('get-data');
        $group->post('', 'App\Controllers\LoanPurposeController::insert')->setName('insert-data');
        $group->put('/[{id}]', 'App\Controllers\LoanPurposeController::update')->setName('update-data');
        $group->delete('/[{id}]', 'App\Controllers\LoanPurposeController::delete')->setName('delete-data');
    });

    $app->group('/loan/setting', function (Group $group) {
        $group->get('', 'App\Controllers\LoanSettingController::getAll')->setName('get-data');
        $group->post('', 'App\Controllers\LoanSettingController::insert')->setName('insert-data');
        $group->put('/[{id}]', 'App\Controllers\LoanSettingController::update')->setName('update-data');
        $group->delete('/[{id}]', 'App\Controllers\LoanSettingController::delete')->setName('delete-data');
    });

    $app->group('/installment/calculation', function (Group $group) {
        $group->get('', 'App\Controllers\InstallmentController::calculation')->setName('get-installment');
    });

    $app->group('/transaction', function (Group $group) {
        $group->post('', 'App\Controllers\TransactionController::insert')->setName('insert-transaction');
    });

};
