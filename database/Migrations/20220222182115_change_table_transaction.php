<?php

declare(strict_types=1);

use Phoenix\Migration\AbstractMigration;
use Phoenix\Exception\InvalidArgumentValueException;

final class ChangeTableTransaction extends AbstractMigration
{
    /**
     * @throws InvalidArgumentValueException
     */
    protected function up(): void
    {
        $this->table('transaction_details')
            ->drop();

        $this->table('transactions')
            ->drop();

        $this->table('loan_transactions')
            ->addColumn('loan_date', 'datetime')
            ->addColumn('customer_id', 'integer')
            ->addColumn('loan_purpose_id', 'integer')
            ->addColumn('loan_period', 'integer')
            ->addForeignKey('customer_id', 'customers', "id")
            ->addForeignKey('loan_purpose_id', 'loan_purpose', 'id')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();

        $this->table('installments')
            ->addColumn('loan_transaction_id', 'integer')
            ->addColumn('month', 'integer')
            ->addColumn('due_date', 'date')
            ->addColumn('payback_date', 'date', ['null' => true])
            ->addColumn('amount', 'double')
            ->addColumn('payback', 'double', ['null' => true])
            ->addColumn('underpayment', 'double')
            ->addColumn('paid', 'boolean')
            ->addForeignKey('loan_transaction_id', 'loan_transactions', 'id')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();

        $this->table('payments')
            ->addColumn('installment_id', 'integer')
            ->addColumn('payback', 'double', ['null' => true])
            ->addColumn('underpayment', 'double')
            ->addForeignKey('installment_id', 'installments', 'id')
            ->addColumn('created_at', 'datetime')
            ->create();
    }

    /**
     * @throws InvalidArgumentValueException
     */
    protected function down(): void
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

        $this->table('loan_transactions')
            ->drop();

        $this->table('installments')
            ->drop();

        $this->table('payments')
            ->drop();
    }
}
