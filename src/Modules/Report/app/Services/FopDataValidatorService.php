<?php

namespace Modules\Report\Services;

use Illuminate\Support\Facades\Log;
use Modules\Report\DTO\UpdateFopDTO;
use Modules\Report\Enums\FopStepEnum;

class FopDataValidatorService
{
    public function validate(UpdateFopDTO $updateFopDTO): array
    {
        $response = ['messages' => ['ok']];

        if (
            ! $updateFopDTO->fop &&
            (! strlen($updateFopDTO->firstParameter) || !strlen($updateFopDTO->secondParameter) || !strlen($updateFopDTO->thirdParameter))
        ) {
                return ['errors' => [__('report.not_correct_fullname')]];
        } elseif($updateFopDTO->fop) {
            Log::info($updateFopDTO->fop->step);
            $response = match ($updateFopDTO->fop->step) {
                FopStepEnum::ASK_NAME->value => (preg_match('/^\d{10}$/', $updateFopDTO->firstParameter))
                    ? $response
                    : ['errors' => [__('report.not_correct_fop_id')]],
                FopStepEnum::ASK_FOP_ID->value => (preg_match('/^\d{10}$/', $updateFopDTO->firstParameter))
                    ? $response
                    : ['errors' => [__('report.not_correct_tax_id')]],
                FopStepEnum::ASK_TAX_ID->value => (preg_match('/^\d{10}$/', $updateFopDTO->firstParameter))
                    ? $response
                    : ['errors' => [__('report.not_correct_tax_id')]],
                FopStepEnum::COMPLETED->value => ['errors' => [__('report.all_data_saved')]],
                default => ['errors' => [__('report.something_went_wrong')]],
            };;
        }

        return $response;
    }
}
