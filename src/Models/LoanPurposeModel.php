<?php

declare(strict_types=1);

namespace App\Models;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as V;

class LoanPurposeModel
{

    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * Function for validation request
     * @param $request
     * @return array|mixed
     */
    public function validate($request)
    {
        $this->setName($request['name']);

        $customerValidator = v::attribute('name', v::alpha());

        $errorMessage = [];
        try {
            $customerValidator->assert($this);
        } catch (NestedValidationException $ex) {
            $messages = $ex->getMessages();
            foreach ($messages as $message) {
                $errorMessage[] = $message;
            }
        }
        return $errorMessage;
    }

}