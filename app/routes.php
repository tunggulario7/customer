<?php
declare(strict_types=1);

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
        $group->post('', 'App\Controllers\CustomerController::insert')->setName('insert-data');
        $group->get('', 'App\Controllers\CustomerController::get')->setName('get-data');
        $group->get('/data', 'App\Controllers\CustomerController:getCustomer')->setName('get-customer-data');

        $group->get('/db-test', function (Request $request, Response $response) {
            $db = new PDO("mysql:host=db_local;dbname=Tunaiku_Loan;port=3306;", 'root', 'admin123');
            var_dump($db);
            $sth = $db->prepare("SELECT * FROM coba");
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $payload = json_encode($data);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        });
    });

};
