<?php

namespace Modules\Report\Repositories;

use App\Models\Fop;
use App\Models\Report;
use Illuminate\Support\Facades\Log;

class ReportRepository
{
    public function findByFopId(int $chatId): ?Fop
    {
        return Report::where('fop_id', $chatId)->first();
    }

    public function create(array $data): Fop
    {
        return Report::create($data);
    }

    public function update(int $id, array $data): void
    {
        Report::where('id', $id)->update($data);
    }
}
