<?php

namespace Tests\Application\Actions;

use App\Controllers\Installment\Request\InstallmentRequest;
use App\Factory\Connection;
use App\Modules\LoanSetting\Provider\LoanSettingProvider;
use App\Modules\LoanSetting\Service\LoanSettingService;
use Slim\Psr7\Response;
use Tests\TestCase;

class InstallmentTest extends TestCase
{
    public function setResponse(): Response
    {
        return new Response();
    }

    public function setLoanSettingService(): LoanSettingService
    {
        $connection = new Connection();
        $loanSettingProvider = new LoanSettingProvider($connection);
        return new LoanSettingService($loanSettingProvider);
    }

    public function testGetDataProvider(): array
    {
        return [
            [
                [
                    'loanDate' => "2022-02-24",
                    'loanPurpose' => 3,
                    'loanAmount' => 10000,
                ], 200
            ],
        ];
    }

    /** @dataProvider testGetDataProvider */
    public function testGetData(array $bodyJson, int $expectedStatusCode)
    {
        //Create Request Body
        $requestBody = $this->createRequest('POST', '/installment/calculation');
        $json = $requestBody->withQueryParams($bodyJson);

        $installmentRequest = new InstallmentRequest($this->setLoanSettingService());
        $responseData = $installmentRequest->__invoke($json, $this->setResponse(), []);

        $this->assertEquals($expectedStatusCode, $responseData->getStatusCode());
    }
}
