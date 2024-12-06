<?php

namespace Modules\Report\Jobs;

use App\Models\Fop;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Report\Services\XmlReportService;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;

class DownloadXMLReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Fop $fop) {}


    public function handle(): void
    {
        $fop = $this->fop;
        $reportService = app()->make(XmlReportService::class);
//
//      //  if (! strlen($this->fop->report->xml_report_url)) {
            $reportUrl = $reportService->createReport($fop);
//     //   }

        try {
            Log::info('report was saved on ' . $reportUrl);
            $stream = Storage::disk('s3')->getDriver()->readStream($reportUrl);

            if ($stream === false) {
                throw new \Exception('Не вдалося отримати ресурс файлу.');
            }
        } catch (\Exception $exception) {
            Telegram::sendMessage([
                'chat_id' => $fop->chat_id,
                'text' => __('report.something_went_wrong'),
            ]);

            return;
        }

        Telegram::sendDocument([
            'chat_id' => $fop->chat_id,
            'document' => InputFile::create($stream, 'report.xml'),
            'caption' => __('report.your_report'),
        ]);
    }
}
