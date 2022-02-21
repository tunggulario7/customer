<?php

declare(strict_types=1);

use Phoenix\Exception\InvalidArgumentValueException;
use Phoenix\Migration\AbstractMigration;

final class Transaction_detail_table extends AbstractMigration
{
    /**
     * @throws InvalidArgumentValueException
     */
    protected function up(): void
    {
        $this->table('transaction_details')
            ->addColumn('transaction_id', 'integer')
            ->addColumn('month', 'integer')
            ->addColumn('due_date', 'date')
            ->addColumn('payback_date', 'date', ['null' => true])
            ->addColumn('amount', 'double')
            ->addColumn('payback', 'double', ['null' => true])
            ->addColumn('underpayment', 'double')
            ->addColumn('paid', 'boolean')
            ->addForeignKey('transaction_id', 'transactions', 'id')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }

    protected function down(): void
    {
        $this->table('transaction_details')
            ->drop();
    }
}
