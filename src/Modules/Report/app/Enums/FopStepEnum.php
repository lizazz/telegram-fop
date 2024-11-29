<?php

namespace Modules\Report\Enums;

enum FopStepEnum: string
{
    case ASK_NAME = 'ask_name';
    case ASK_TAX_ID = 'ask_tax_id';
    case ASK_FOP_ID = 'ask_fop_id';
    case COMPLETED = 'completed';

    public function statuses()
    {
        return match ($this) {
            FopStepEnum::ASK_NAME => 'ask_name',
            FopStepEnum::ASK_TAX_ID => 'ask_tax_id',
            FopStepEnum::COMPLETED => 'completed',
            FopStepEnum::ASK_FOP_ID => 'ask_fop_id',
        };
    }
}
