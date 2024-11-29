<?php

namespace Modules\Report\Services;

use App\Models\Fop;
use Illuminate\Support\Facades\Log;
use Modules\Report\DTO\UpdateFopDTO;
use Modules\Report\Enums\FopStepEnum;
use Modules\Report\Repositories\FopRepository;

class FopService
{
    private FopRepository $repository;
    public function __construct()
    {
        $this->repository = app()->make(FopRepository::class);
    }

    public function getFopByChatId(int $chatId): ?Fop
    {
        return $this->repository->findByChatId($chatId);
    }

    public function createFop(int $chatId, string $fullname): Fop
    {
        return $this->repository->create([
            'chat_id' => $chatId,
            'name' => $fullname,
            'step' => FopStepEnum::ASK_NAME->value
        ]);
    }

    public function updateForByChatId(UpdateFopDTO $updateFopDTO): string
    {
        return match ($updateFopDTO->fop->step) {
            FopStepEnum::ASK_NAME->value => $this->performUpdate(
                $updateFopDTO->fop->id,
                ['step' => FopStepEnum::ASK_FOP_ID, 'fop_id' => $updateFopDTO->firstParameter],
                __('report.fill_tax_number')
            ),
            FopStepEnum::ASK_FOP_ID->value => $this->performUpdate(
                $updateFopDTO->fop->id,
                ['step' => FopStepEnum::COMPLETED, 'tax_id' => $updateFopDTO->firstParameter],
                __('report.all_data_saved')
            ),
        };
    }

    private function performUpdate(int $fopId, array $data, string $message): string
    {
        Log::info('performUpdate');
        $this->repository->update($fopId, $data);
        return $message;
    }
}
