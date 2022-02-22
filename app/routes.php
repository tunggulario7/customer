<?php

declare(strict_types=1);

use App\Controllers\Customer\Request\AddCustomerRequest;
use App\Controllers\Customer\Request\DeleteCustomerRequest;
use App\Controllers\Customer\Request\GetAllCustomerRequest;
use App\Controllers\Customer\Request\UpdateCustomerRequest;
use App\Controllers\LoanPurpose\Request\AddLoanPurposeRequest;
use App\Controllers\LoanPurpose\Request\DeleteLoanPurposeRequest;
use App\Controllers\LoanPurpose\Request\GetAllLoanPurposeRequest;
use App\Controllers\LoanPurpose\Request\UpdateLoanPurposeRequest;
use App\Controllers\LoanSetting\Request\AddLoanSettingRequest;
use App\Controllers\LoanSetting\Request\DeleteLoanSettingRequest;
use App\Controllers\LoanSetting\Request\GetAllLoanSettingRequest;
use App\Controllers\LoanSetting\Request\UpdateLoanSettingRequest;
use App\Controllers\Installment\Request\InstallmentRequest;
use App\Controllers\LoanTransaction\Request\AddTransactionRequest;
use App\Controllers\Payment\Request\PaymentRequest;
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
        $group->get('', GetAllCustomerRequest::class)->setName('get-data');
        $group->post('', AddCustomerRequest::class)->setName('insert-data');
        $group->put('/[{id}]', UpdateCustomerRequest::class)->setName('update-data');
        $group->delete('/[{id}]', DeleteCustomerRequest::class)->setName('delete-data');
    });

    $app->group('/loan/purpose', function (Group $group) {
        $group->get('', GetAllLoanPurposeRequest::class)->setName('get-data');
        $group->post('', AddLoanPurposeRequest::class)->setName('insert-data');
        $group->put('/[{id}]', UpdateLoanPurposeRequest::class)->setName('update-data');
        $group->delete('/[{id}]', DeleteLoanPurposeRequest::class)->setName('delete-data');
    });

    $app->group('/loan/setting', function (Group $group) {
        $group->get('', GetAllLoanSettingRequest::class)->setName('get-data');
        $group->post('', AddLoanSettingRequest::class)->setName('insert-data');
        $group->put('/[{id}]', UpdateLoanSettingRequest::class)->setName('update-data');
        $group->delete('/[{id}]', DeleteLoanSettingRequest::class)->setName('delete-data');
    });

    $app->group('/installment/calculation', function (Group $group) {
        $group->get('', InstallmentRequest::class)->setName('get-installment');
    });

    $app->group('/transaction', function (Group $group) {
        $group->post('', AddTransactionRequest::class)->setName('insert-transaction');
    });

    $app->group('/payment', function (Group $group) {
        $group->post('', PaymentRequest::class)->setName('payment-calculation');
    });

};
