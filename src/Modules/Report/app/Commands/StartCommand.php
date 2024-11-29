<?php

namespace Modules\Report\Commands;

use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Commands\CommandInterface;
use Telegram\Bot\Objects\Update;

class StartCommand extends Command implements CommandInterface
{
    protected string $name = "start";
    protected string $description = "Start conversation with the bot";
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

    public function make(Api $telegram, Update $update, array $entity): mixed
    {
        $chatId = $update->getChat()->getId();
        return $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('report.greeting')
        ]);
    }

    public function handle()
    {
        Log::info("Starting conversation with the bot");
        $chatId = $this->getUpdate()->getChat()->getId();
        $this->replyWithMessage([
            'chat_id' => $chatId,
            'text' => 'Welcome on board!'
        ]);
    }
}
