<?php

namespace App\Traits\Modify;

use Illuminate\Support\Carbon;

trait LogsUpdatedAt
{
    public function logTouch()
    {
        $this->forceFill(['updated_at' => Carbon::now()])->save();
    }
}