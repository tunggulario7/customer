<?php

declare(strict_types=1);

namespace App\Controllers\Customer\Model;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Factory;
use Respect\Validation\Validator as V;

class Customer
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
        try {
            $this->name = $name;
        } catch (\Throwable $e) {
            $this->name = '0';
        }
    }

    public function getKtp(): int
    {
        return $this->ktp;
    }

    public function setKtp($ktp): void
    {
        try {
            $this->ktp = $ktp;
        } catch (\Throwable $e) {
            $this->ktp = 0;
        }
    }

    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth($dateOfBirth): void
    {
        try {
            $this->dateOfBirth = $dateOfBirth;
        } catch (\Throwable $e) {
            $this->dateOfBirth = '0';
        }
    }

    public function getSex(): string
    {
        return $this->sex;
    }

    public function setSex($sex): void
    {
        try {
            $this->sex = $sex;
        } catch (\Throwable $e) {
            $this->sex = '0';
        }
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress($address): void
    {
        try {
            $this->address = $address;
        } catch (\Throwable $e) {
            $this->address = '';
        }
    }

    /**
     * Function for validation request
     * @param $request
     * @return array|mixed
     */
    public function validate($request, $id = 0)
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
            ->attribute('ktp', v::KtpRule($this->getDateOfBirth(), $this->getSex(), (int) $id))
            ->attribute('dateOfBirth', v::date())
            ->attribute('sex', v::in(['M', 'F']));

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
