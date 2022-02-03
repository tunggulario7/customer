<?php

return [
    'migration_dirs' => [
        'migrations' => 'database/Migrations'
    ],
    'environments' => [
        'local' => [
            'adapter' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'username' => 'root',
            'password' => 'admin123',
            'db_name' => 'Tunaiku_Loan',
            'charset' => 'utf8mb4',
        ],
        'production' => [
            'adapter' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 3357,
            'username' => 'root',
            'password' => 'admin123',
            'db_name' => 'Tunaiku_Loan',
            'charset' => 'utf8mb4',
        ],
    ],
    'default_environment' => 'local',
    'log_table_name' => 'phoenix_log',
];