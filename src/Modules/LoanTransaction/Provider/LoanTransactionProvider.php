<?php

declare(strict_types=1);

namespace App\Modules\LoanTransaction\Provider;

use App\Factory\Connection;
use PDO;

class LoanTransactionProvider
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
     * function Get All Loan Transaction Data
     * @return array
     */
    public function getAll(): array
    {
        $sqlQuery = "SELECT loan_date AS loanDate, CS.name, CS.ktp, CS.date_of_birth AS dateOfBirth, LP.name AS loanPurpose  FROM loan_transactions TR
    INNER JOIN customers CS ON CS.id = TR.customer_id
    INNER JOIN loan_purpose LP ON LP.id = TR.loan_purpose_id";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Get by ID Loan Transaction Data
     * @param $id
     * @return array
     */
    public function getById($id): array
    {
        $sqlQuery = "SELECT loan_date AS loanDate, CS.name, CS.ktp, CS.date_of_birth AS dateOfBirth, LP.name AS loanPurpose FROM loan_transactions TR
    INNER JOIN customers CS ON CS.id = TR.customer_id
    INNER JOIN loan_purpose LP ON LP.id = TR.loan_purpose_id WHERE TR.id=:id";
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
     * function Get by ID Loan Transaction Data
     * @param $id
     * @return array
     */
    public function getByCustomer($customerId): array
    {
        $sqlQuery = "SELECT TR.id AS loanId, loan_date AS loanDate, CS.name, CS.ktp, CS.date_of_birth AS dateOfBirth, LP.name AS loanPurpose FROM loan_transactions TR
    INNER JOIN customers CS ON CS.id = TR.customer_id
    INNER JOIN loan_purpose LP ON LP.id = TR.loan_purpose_id WHERE TR.customer_id=:customer_id ORDER BY TR.id ASC";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->bindParam("customer_id", $customerId);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Insert Loan Transaction Data
     * @param $data
     * @return string
     */
    public function insert($field, $value, $params): string
    {
        $sqlQuery = "INSERT INTO loan_transactions ($field) VALUES ($value)";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        foreach ($params as $param) {
            $query->bindParam($param['field'], $param['value']);
        }
        $query->execute();

        return (string) $pdo->lastInsertId();
    }

    /**
     * function Delete Loan Transaction Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $sqlQuery = "DELETE FROM loan_transactions WHERE id=:id";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":id", $id['id']);
        $query->execute();

        return $id['id'];
    }

}