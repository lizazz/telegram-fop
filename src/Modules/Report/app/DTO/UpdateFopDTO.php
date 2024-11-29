<?php

namespace Modules\Report\DTO;

use App\Models\Fop;
use Spatie\LaravelData\Data;

class UpdateFopDTO extends Data
{
    public function __construct(
        public ?Fop $fop,
        public ?string $firstParameter,
        public ?string $secondParameter = '',
        public ?string $thirdParameter = ''
    ) {}
}
