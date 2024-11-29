<?php

namespace Modules\Report\Enums;

enum ReportTypeEnum: string
{
    case XML = 'xml';
    case XLSX = 'xlsx';

    public function statuses()
    {
        return match ($this) {
            ReportTypeEnum::XML => 'xml',
            ReportTypeEnum::XLSX => 'xlsx',
        };
    }
}
