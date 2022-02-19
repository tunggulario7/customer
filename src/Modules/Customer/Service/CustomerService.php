<?php

declare(strict_types=1);

namespace App\Modules\Customer\Service;

use App\Modules\Customer\Provider\CustomerProvider;

class CustomerService
{
    protected CustomerProvider $customerProvider;

    public function __construct(CustomerProvider $customerProvider)
    {
        $this->customerProvider = $customerProvider;
    }

    /**
     * function Get All Customer Data
     * @return array
     */
    public function getAll(): array
    {
        return $this->customerProvider->getAll();
    }

    /**
     * function Get by ID Customer Data
     * @param $id
     * @return array
     */
    public function getById($id): array
    {
        return $this->customerProvider->getById($id);
    }

    /**
     * function Get by ID Customer Data
     * @param $field
     * @param $value
     * @return array
     */
    public function getByField($field, $value): array
    {
        return $this->customerProvider->getByField($field, $value);
    }

    /**
     * function Get by ID Customer Data
     * @param $field
     * @param $value
     * @return array
     */
    public function getByFieldWithId($field, $value, $id): array
    {
        return $this->customerProvider->getByFieldWithId($field, $value, $id);
    }

    /**
     * function Insert Customer Data
     * @param $data
     * @return string
     */
    public function insert($data): string
    {
        $dateNow = date("Y-m-d H:i:s");
        $field = "name, ktp, date_of_birth, sex, address, created_at";
        $value = ":name, :ktp, :date_of_birth, :sex, :address, :created_at";
        $params = [
            [
                "field" => ":name",
                "value" => $data['name']
            ],
            [
                "field" => ":ktp",
                "value" => $data['ktp']
            ],
            [
                "field" => ":date_of_birth",
                "value" => $data['dateOfBirth']
            ],
            [
                "field" => ":sex",
                "value" => $data['sex']
            ],
            [
                "field" => ":address",
                "value" => $data['address']
            ],
            [
                "field" => ":created_at",
                "value" => $dateNow
            ]
        ];

        return $this->customerProvider->insert($field, $value, $params);
    }

    /**
     * function Insert Customer Data
     * @param $data
     * @param $id
     * @return string
     */
    public function update($data, $id): string
    {
        $dateNow = date("Y-m-d H:i:s");
        $sql = "UPDATE customers SET ";
        $sqlQuery = '';
        $setField = 'updated_at = :updated_at';

        //Set Field Update
        foreach ($data as $itemId => $value) {
            $setField = $setField . ',' . $itemId . '=' . "'" . $value . "'";
        }

        //Set Query String
        $sqlQuery .= $sql . $setField . ' WHERE id = :id';

        $this->customerProvider->update($sqlQuery, $dateNow, $id);

        return (string) $id['id'];
    }

    /**
     * function Delete Customer Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $this->customerProvider->delete($id);

        return $id['id'];
    }
}
