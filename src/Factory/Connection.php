<?php

declare(strict_types=1);

namespace App\Factory;

use PDO;

class Connection
{
    private string $host = 'db_local';
    private int $port = 3306;
    private string $user = 'root';
    private string $pass = 'admin123';
    private string $dbname = 'Tunaiku_Loan';


    public function connect()
    {
        $conn_str = "mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8mb4";
        $conn = new PDO($conn_str, $this->user, $this->pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }

}