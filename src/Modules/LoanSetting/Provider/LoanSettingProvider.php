<?php

namespace App\Modules\LoanSetting\Provider;

use App\Factory\Connection;
use PDO;

class LoanSettingProvider
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @param Connection $connection
     */
    public function setConnection(Connection $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * function Get All Loan Setting Data
     * @return array
     */
    public function getAll(): array
    {
        $sqlQuery = "SELECT LS.id, name AS loanPurpose, period FROM loan_settings LS INNER JOIN loan_purpose LP ON LP.id = LS.loan_purpose_id";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Get by ID Loan Setting Data
     * @param $id
     * @return array
     */
    public function getById($id): array
    {
        $sqlQuery = "SELECT LS.id, name AS loanPurpose, period FROM loan_settings LS INNER JOIN loan_purpose LP ON LP.id = LS.loan_purpose_id WHERE LS.id=:id";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->bindParam("id", $id);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return [];
        }
        return $data;
    }

    /**
     * function Get by ID Loan Setting Data
     * @param $loanPurposeid
     * @return array
     */
    public function getByLoanPurpose($loanPurposeid): array
    {
        $sqlQuery = "SELECT LS.id, name AS loanPurpose, period FROM loan_settings LS INNER JOIN loan_purpose LP ON LP.id = LS.loan_purpose_id WHERE loan_purpose_id=:loan_purpose_id";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->bindParam("loan_purpose_id", $loanPurposeid);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Insert Loan Setting Data
     * @param $data
     * @return string
     */
    public function insert($field, $value, $params): string
    {
        $dateNow = date("Y-m-d H:i:s");
        $sqlQuery = "INSERT INTO loan_settings ($field) VALUES ($value)";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        foreach ($params as $param) {
            $query->bindParam($param['field'], $param['value']);
        }
        $query->execute();

        return (string) $pdo->lastInsertId();
    }

    /**
     * function Insert Loan Setting Data
     * @param $data
     * @param $id
     * @return string
     */
    public function update($sqlQuery, $dateNow, $id): string
    {
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":updated_at", $dateNow);
        $query->bindParam(":id", $id['id']);
        $query->execute();

        return (string) $id['id'];
    }

    /**
     * function Delete Loan Setting Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $sqlQuery = "DELETE FROM loan_settings WHERE id=:id";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":id", $id['id']);
        $query->execute();

        return $id['id'];
    }
}
