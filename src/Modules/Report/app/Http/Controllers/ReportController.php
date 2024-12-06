<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Telegram\Bot\Api;

class ReportController extends Controller
{
    public function __construct(readonly private Api $telegram) {}

    public function start(): void
    {
        $this->telegram->commandsHandler(true);
    }

    public function setWebhook(): JsonResponse
    {
        try {
            $response = $this->telegram->setWebhook([
                'url' => env('TELEGRAM_WEBHOOK_URL'),
            ]);

            $this->telegram->commandsHandler(true);
        } catch (\Exception $exception) {
            return response()->json(['errors' => [$exception->getMessage()]]);
        }

        return response()->json(['message' => json_encode($response)]);
    }
}
