<?php

declare(strict_types=1);

namespace App\Services;

use App\Factory\Connection;
use PDO;

class CustomerService
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
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * function Insert Customer Data
     * @param $data
     * @return string
     */
    public function insert($data): string
    {
        $dateNow = date("Y-m-d H:i:s");
        $sqlQuery = "INSERT INTO customers (name, ktp, date_of_birth, sex, address, created_at) VALUES (:name, :ktp, :date_of_birth, :sex, :address, :created_at)";
        $pdo = $this->getConnection()->connect();
        $query = $pdo->prepare($sqlQuery);
        $query->bindParam(":name", $data['name']);
        $query->bindParam(":ktp", $data['ktp']);
        $query->bindParam(":date_of_birth", $data['dateOfBirth']);
        $query->bindParam(":sex", $data['sex']);
        $query->bindParam(":address", $data['address']);
        $query->bindParam(":created_at", $dateNow);
        $query->execute();

        return (string) $pdo->lastInsertId();
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
