<?php

declare(strict_types=1);

namespace App\Modules\Payment\Provider;

use App\Factory\Connection;
use PDO;

class PaymentProvider
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
     * function Get All Payment Data
     * @param $installmentId
     * @return array
     */
    public function getAllByInstallment($installmentId): array
    {
        $sqlQuery = "SELECT * FROM payments WHERE installment_id =:installment_id";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->bindParam("installment_id", $installmentId);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Insert Payment Data
     * @param $field
     * @param $value
     * @param $params
     * @return string
     */
    public function insert($field, $value, $params): string
    {
        $sqlQuery = "INSERT INTO payments ($field) VALUES ($value)";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        foreach ($params as $param) {
            $query->bindParam($param['field'], $param['value']);
        }
        $query->execute();

        return (string) $pdo->lastInsertId();
    }

    /**
     * function Delete Payment Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $sqlQuery = "DELETE FROM payments WHERE id=:id";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":id", $id['id']);
        $query->execute();

        return $id['id'];
    }

}