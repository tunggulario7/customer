<?php

declare(strict_types=1);

use Phoenix\Exception\InvalidArgumentValueException;
use Phoenix\Migration\AbstractMigration;

final class Loan_setting_table extends AbstractMigration
{
    /**
     * @throws InvalidArgumentValueException
     */
    protected function up(): void
    {
        $this->table('loan_settings')
            ->addColumn('loan_purpose_id', 'integer')
            ->addColumn('period', 'integer')
            ->addForeignKey('loan_purpose_id', 'loan_purpose', 'id')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }

    protected function down(): void
    {
        $this->table('loan_settings')
            ->drop();
    }
}
