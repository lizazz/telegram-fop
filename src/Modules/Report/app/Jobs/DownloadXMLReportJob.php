<?php

namespace Modules\Report\Jobs;

use App\Models\Fop;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Report\Services\XmlReportService;
use Telegram\Bot\Laravel\Facades\Telegram;

class DownloadXMLReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Fop $fop) {}


    public function handle(): void
    {
        $fop = $this->fop;
        $reportService = app()->make(XmlReportService::class);

        if (! strlen($this->fop->report->xml_report_url)) {
            $reportUrl = $reportService->createReport($fop);
        }

        Telegram::sendDocument([
            'chat_id' => $fop->chat_id,
            'document' => fopen($reportUrl, 'r'),
            'caption' => __('report.your_report'),
        ]);
    }
}
