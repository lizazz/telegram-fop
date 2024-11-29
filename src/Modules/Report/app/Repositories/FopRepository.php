<?php

namespace Modules\Report\Repositories;

use App\Models\Fop;
use Illuminate\Support\Facades\Log;

class FopRepository
{
    public function findByChatId(int $chatId): ?Fop
    {
        return Fop::where('chat_id', $chatId)->first();
    }

    public function create(array $data): Fop
    {
        return Fop::create($data);
    }

    public function update(int $id, array $data): void
    {
        Fop::where('id', $id)->update($data);
    }
}
