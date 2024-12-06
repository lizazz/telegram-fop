<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Fop extends Model
{
    protected $guarded = ['id'];

    public function report(): HasOne
    {
        return $this->hasOne(Report::class, 'fop_id', 'id');
    }
}
