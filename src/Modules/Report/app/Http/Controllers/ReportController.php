<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Report\Commands\StartCommand;
use Modules\Report\Enums\FopStepEnum;
use Telegram\Bot\Actions;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;

class ReportController extends Controller
{
    public function __construct(private Api $telegram) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('report::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('report::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = $this->telegram->setWebhook([
            'url' => env('TELEGRAM_WEBHOOK_URL'),
        ]);
       // $response = $this->telegram->sendMessage(['text' => ])
//        $response = $this->telegram->getMe();
        dd($response);
//        dd($request->all());
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('report::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('report::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function start()
    {
        $this->telegram->commandsHandler(true);
    }

    public function setWebhook(Request $request)
    {
//        $fop = Fop::find(1);
//        dd(FopStepEnum::ASK_NAME->value);
//        dd('ok');
        $response = $this->telegram->setWebhook([
            'url' => env('TELEGRAM_WEBHOOK_URL'),
        ]);
       // $response = $this->telegram->addCommand(StartCommand::class);
        $update = $this->telegram->commandsHandler(true);
        //dd($update);
        response()->json(['message' => json_encode($response)]);
    }
}
