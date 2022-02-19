<?php

declare(strict_types=1);

namespace App\Controllers\LoanPurpose\Model;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as V;

class LoanPurpose
{

    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        try {
            $this->name = $name;
        } catch (\Throwable $e) {
            $this->name = '0';
        }
    }

    /**
     * Function for validation request
     * @param $request
     * @return array|mixed
     */
    public function validate($request)
    {
        $this->setName($request['name']);

        $customerValidator = v::attribute('name', v::alpha(' '));

        $errorMessage = [];
        try {
            $customerValidator->assert($this);
        } catch (NestedValidationException $ex) {
            $messages = $ex->getMessages();
            foreach ($messages as $key => $message) {
                $errorMessage[] = [
                    "status" => $key,
                    "message" => "Failed Validation",
                    "errors" => $message
                ];
            }
        }
        return $errorMessage;
    }

}