<?php

namespace Modules\Report\Commands;

use Illuminate\Support\Facades\Log;
use Modules\Report\Enums\ReportTypeEnum;
use Modules\Report\Jobs\DownloadXMLReportJob;
use Modules\Report\Services\FopService;
use Modules\Report\Services\ReportDataValidatorService;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Commands\CommandInterface;

class DownloadCommand extends Command implements CommandInterface
{
    protected string $name = "download";
    protected string $description = "Download report";
    protected string $pattern = '{type}';
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
        $fopDataValidatorService = app()->make(ReportDataValidatorService::class);
        $fop = $fopService->getFopByChatId($chatId);
        $validatedData = $fopDataValidatorService->validate($fop);

        if (isset($validatedData['errors'])) {
            Log::info("eeror " . $validatedData['errors'][0]);
            $this->replyWithMessage([
                'text' => $validatedData['errors'][0],
            ]);

            return;
        }

        $type = $this->argument('type');

        match ($type) {
            ReportTypeEnum::XML->value => DownloadXMLReportJob::dispatch($fop),
            ReportTypeEnum::XLSX->value => DownloadXMLReportJob::dispatch($fop),
            default => null,
        };
    }
}
