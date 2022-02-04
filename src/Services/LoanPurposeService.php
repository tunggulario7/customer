<?php

declare(strict_types=1);

namespace App\Services;

use App\Factory\Connection;
use PDO;

class LoanPurposeService
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
     * function Get All Loan Purpose Data
     * @return array
     */
    public function getAll(): array
    {
        $sqlQuery = "SELECT * FROM loan_purpose";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Get by ID Loan Purpose Data
     * @param $id
     * @return array
     */
    public function getById($id): array
    {
        $sqlQuery = "SELECT * FROM loan_purpose WHERE id=:id";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->bindParam("id", $id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Insert Loan Purpose Data
     * @param $data
     * @return string
     */
    public function insert($data): string
    {
        $sqlQuery = "INSERT INTO loan_purpose (name) VALUES (:name)";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":name", $data['name']);
        $query->execute();

        return (string) $pdo->lastInsertId();
    }

    /**
     * function Insert Loan Purpose Data
     * @param $data
     * @param $id
     * @return string
     */
    public function update($data, $id): string
    {
        $sqlQuery = "UPDATE loan_purpose SET name = :name WHERE id = :id";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":name", $data['name']);
        $query->bindParam(":id", $id['id']);
        $query->execute();

        return (string) $id['id'];
    }

    /**
     * function Delete Loan Purpose Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $sqlQuery = "DELETE FROM loan_purpose WHERE id=:id";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":id", $id['id']);
        $query->execute();

        return $id['id'];
    }

}