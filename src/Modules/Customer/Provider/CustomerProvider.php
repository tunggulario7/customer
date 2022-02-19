<?php

namespace App\Modules\Customer\Provider;

use App\Factory\Connection;
use PDO;

class CustomerProvider
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
     * function Get All Customer Data
     * @return array
     */
    public function getAll(): array
    {
        $sqlQuery = "SELECT * FROM customers";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Get by ID Customer Data
     * @param $id
     * @return array
     */
    public function getById($id): array
    {
        $sqlQuery = "SELECT * FROM customers WHERE id=:id";
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
     * function Get by ID Customer Data
     * @param $field
     * @param $value
     * @return array
     */
    public function getByField($field, $value): array
    {
        $sqlQuery = "SELECT * FROM customers WHERE ". $field ."=:field";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->bindParam("field", $value);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Get by ID Customer Data
     * @param $field
     * @param $value
     * @return array
     */
    public function getByFieldWithId($field, $value, $id): array
    {
        $sqlQuery = "SELECT * FROM customers WHERE ". $field ."=:field AND id=:id";
        $query = $this->getConnection()->connect()->prepare($sqlQuery);
        $query->bindParam("field", $value);
        $query->bindParam("id", $id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * function Insert Customer Data
     * @param $data
     * @return string
     */
    public function insert($field, $value, $params): string
    {
        $sqlQuery = "INSERT INTO customers ($field) VALUES ($value)";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        foreach ($params as $param) {
            $query->bindParam($param['field'], $param['value']);
        }
        $query->execute();

        return (string) $pdo->lastInsertId();
    }

    /**
     * function Insert Customer Data
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
     * function Delete Customer Data
     * @param $id
     * @return string
     */
    public function delete($id): string
    {
        $sqlQuery = "DELETE FROM customers WHERE id=:id";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":id", $id['id']);
        $query->execute();

        return $id['id'];
    }

}