<?php

declare(strict_types=1);

namespace App\Modules\LoanSetting\Service;

use App\Modules\LoanSetting\Provider\LoanSettingProvider;

class LoanSettingService
{
    private LoanSettingProvider $loanSettingProvider;

    public function __construct(LoanSettingProvider $loanSettingProvider)
    {
        $this->loanSettingProvider = $loanSettingProvider;
    }

    /**
     * function Get All Loan Setting Data
     * @return array
     */
    public function getAll(): array
    {
        return $this->loanSettingProvider->getAll();
    }

    /**
     * function Get by ID Loan Setting Data
     * @param $id
     * @return array
     */
    public function getById($id): array
    {
        return $this->loanSettingProvider->getById($id);
    }

    /**
     * function Get by ID Loan Setting Data
     * @param $loanPurposeid
     * @return array
     */
    public function getByLoanPurpose($loanPurposeid): array
    {
        return $this->loanSettingProvider->getByLoanPurpose($loanPurposeid);
    }

    /**
     * function Insert Loan Setting Data
     * @param $data
     * @return string
     */
    public function insert($data): string
    {
        $field = "loan_purpose_id, period, created_at";
        $value = ":loan_purpose_id, :period, :created_at";
        $params = [
            [
                "field" => ":loan_purpose_id",
                "value" => $data['loanPurposeId']
            ],
            [
                "field" => ":period",
                "value" => $data['period']
            ],
            [
                "field" => ":created_at",
                "value" => date("Y-m-d H:i:s")
            ]
        ];

        return $this->loanSettingProvider->insert($field, $value, $params);
    }

    /**
     * function Update Loan Setting Data
     * @param $data
     * @param $id
     * @return string
     */
    public function update($data, $id): string
    {
        $dateNow = date("Y-m-d H:i:s");
        $sql = "UPDATE loan_settings SET ";
        $sqlQuery = '';
        $setField = 'updated_at = :updated_at';

        //Set Field Update
        foreach ($data as $itemId => $value) {
            $setField = $setField . ',' . $itemId . '=' . "'" . $value . "'";
        }

        //Set Query String
        $sqlQuery .= $sql . $setField . ' WHERE id = :id';

        $this->loanSettingProvider->update($sqlQuery, $dateNow, $id);

        return (string) $id['id'];
    }

    /**
     * function Delete Loan Setting Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $this->loanSettingProvider->delete($id);

        return $id['id'];
    }

}