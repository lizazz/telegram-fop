<?php

namespace Modules\Report\Services;

use App\Models\Fop;

class ReportDataValidatorService
{
    public function validate(?Fop $fop): array
    {
        $response = ['messages' => ['ok']];

        if (!$fop) {
            return ['errors' => [__('report.data_is_empty')]];
        }

        if (! strlen($fop->name)) {
            $response = ['errors' => [__('report.not_correct_fop_id')]];
        }

        if (! (preg_match('/^\d{10}$/', $fop->fop_id))) {
            $response = ['errors' => [__('report.not_correct_fop_id')]];
        }

        if (! (preg_match('/^\d{10}$/', $fop->tax_id))) {
            $response = ['errors' => [__('report.not_correct_tax_id')]];
        }

        return $response;
    }
}
