<?php

declare(strict_types=1);

use Phoenix\Exception\InvalidArgumentValueException;
use Phoenix\Migration\AbstractMigration;

final class Transaction_table extends AbstractMigration
{
    /**
     * @throws InvalidArgumentValueException
     */
    protected function up(): void
    {
        $this->table('transactions')
            ->addColumn('transaction_date', 'datetime')
            ->addColumn('customer_id', 'integer')
            ->addColumn('loan_purpose_id', 'integer')
            ->addColumn('loan_period', 'integer')
            ->addForeignKey('customer_id', 'customers', "id")
            ->addForeignKey('loan_purpose_id', 'loan_purpose', 'id')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }

    protected function down(): void
    {
        $this->table('transactions')
            ->drop();
    }
}
