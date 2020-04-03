<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public function path()
    {
        return route('accounts.show', $this);
    }
}
