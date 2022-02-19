<?php

declare(strict_types=1);

namespace App\Modules\Installment\Model;

class Installment
{
    protected InstallmentInterface $installmentModel;
    public function setInstallmentModel(InstallmentInterface $installmentModel): self
    {
        $this->installmentModel = $installmentModel;
        return $this;
    }

    public function getInstallments(): array
    {
        $this->installmentModel->calculate();

        $installmentData = [];
        for ($i = 1; $i <= $this->installmentModel->getPeriod(); $i++) {
            $period = $i * 30;
            $datePeriod = '+' . $period . ' days';

            $dueDate = date('Y-m-d', strtotime($datePeriod, strtotime($this->installmentModel->getLoanDate())));

            $installmentData[] = [
                'period' => $i,
                'dueDate' => $dueDate,
                'installment' => $this->installmentModel->getAmount()
            ];
        }
        return $installmentData;
    }
}
