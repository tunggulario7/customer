<?php

declare(strict_types=1);

namespace App\Models;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Factory;
use Respect\Validation\Validator as V;

class CustomerModel
{

    private string $name;
    private int $ktp;
    private string $dateOfBirth;
    private string $sex;
    private string $address;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getKtp(): int
    {
        return $this->ktp;
    }

    public function setKtp($ktp): void
    {
        $this->ktp = $ktp;
    }

    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth($dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    public function getSex(): string
    {
        return $this->sex;
    }

    public function setSex($sex): void
    {
        $this->sex = $sex;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * Function for validation request
     * @param $request
     * @return array|mixed
     */
    public function validate($request)
    {
        Factory::setDefaultInstance(
            (new Factory())
                ->withRuleNamespace('App\\Validation\\Rules')
                ->withExceptionNamespace('App\\Validation\\Exceptions')
        );

        $this->setName($request['name']);
        $this->setKtp($request['ktp']);
        $this->setDateOfBirth($request['dateOfBirth']);
        $this->setSex($request['sex']);
        $this->setAddress($request['address']);

        $customerValidator = v::attribute('name', v::alpha(' '))
            ->attribute('ktp', v::KtpRule($this->getDateOfBirth(), $this->getSex()))
            ->attribute('dateOfBirth', v::date())
            ->attribute('sex', v::in(['M', 'F']));

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
