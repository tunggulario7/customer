<?php

declare(strict_types=1);

use Phoenix\Exception\InvalidArgumentValueException;
use Phoenix\Migration\AbstractMigration;

final class CustomerTable extends AbstractMigration
{
    /**
     * @throws InvalidArgumentValueException
     */
    protected function up(): void
    {
        $this->table('customers')
            ->addColumn('name', 'string')
            ->addColumn('ktp', 'biginteger')
            ->addColumn('date_of_birth', 'date')
            ->addColumn('sex', 'enum', ['values' => ['M', 'F']])
            ->addColumn('address', 'text')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }

    protected function down(): void
    {
        $this->table('customers')
        ->drop();
    }
}
