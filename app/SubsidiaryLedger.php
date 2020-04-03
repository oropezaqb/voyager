<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubsidiaryLedger extends Model
{
    public function path()
    {
        return route('subsidiary_ledgers.show', $this);
    }
}
