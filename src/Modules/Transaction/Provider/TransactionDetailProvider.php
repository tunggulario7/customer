<?php

namespace App\Modules\Transaction\Provider;

use App\Factory\Connection;
use PDO;

class TransactionDetailProvider
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
     * function Get All Transaction Detail Data
     * @return array
     */
    public function getAllByTransactionId($transactionId): array
    {
        $sqlQuery = "SELECT *  FROM transaction_details WHERE transaction_id =:transaction_id";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->bindParam("transaction_id", $transactionId);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Insert Transaction Detail Data
     * @param $data
     * @return string
     */
    public function insert($field, $value, $params): string
    {
        $sqlQuery = "INSERT INTO transaction_details ($field) VALUES ($value)";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        foreach ($params as $param) {
            $query->bindParam($param['field'], $param['value']);
        }
        $query->execute();

        return (string) $pdo->lastInsertId();
    }

    /**
     * function Delete Transaction Detail Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $sqlQuery = "DELETE FROM transaction_details WHERE id=:id";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":id", $id['id']);
        $query->execute();

        return $id['id'];
    }

}