<?php

namespace Modules\Report\Commands;

use Illuminate\Support\Facades\Log;
use Modules\Report\DTO\UpdateFopDTO;
use Modules\Report\Services\FopDataValidatorService;
use Modules\Report\Services\FopService;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Commands\CommandInterface;

class CreateReportCommand extends Command implements CommandInterface
{
    protected string $name = "create_report";
    protected string $description = "Fill user data for a report";
    protected string $pattern = '{first_arg} {second_arg} {third_arg}';

    public function getName(): string
    {
        return $this->name;
    }

    public function getAliases(): array
    {
        return [];
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getArguments(): array
    {
        return [];
    }

    public function handle()
    {
        $chatId = $this->getUpdate()->getChat()->getId();

        $fopService = app()->make(FopService::class);
        $fopDataValidatorService = app()->make(FopDataValidatorService::class);

        $fop = $fopService->getFopByChatId($chatId);

        $firstName = $this->argument('first_arg');
        $lastName = $this->argument('second_arg');
        $middleName = $this->argument('third_arg');
        $updateFopDTO = new UpdateFopDTO($fop, $firstName, $lastName, $middleName);
        $validatedData = $fopDataValidatorService->validate($updateFopDTO);

        if (isset($validatedData['errors'])) {
            $this->replyWithMessage([
                'text' => $validatedData['errors'][0],
            ]);

            return;
        }

        if (!$fop) {
            $fullname = $lastName . ' ' . $firstName . ' ' . $middleName;
            $fopService->createFop($chatId, $fullname);
            $this->replyWithMessage([
                'text' => __('report.fill_fop_number'),
            ]);
        } else {
            $response = $fopService->updateForByChatId($updateFopDTO);
            Log::info('response is ' . $response);
            $fop->refresh();
            $this->replyWithMessage(['text' => $response]);
        }
    }
}
