<?php

namespace Modules\Report\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Report\DTO\UpdateFopDTO;
use Modules\Report\Enums\ReportTypeEnum;
use Modules\Report\Jobs\DownloadXMLReportJob;
use Modules\Report\Services\FopService;
use Modules\Report\Services\ReportDataValidatorService;
use Modules\Report\Services\XmlReportService;
use SimpleXMLElement;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Commands\CommandInterface;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Objects\Update;

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
