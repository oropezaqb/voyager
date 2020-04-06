<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posting extends Model
{
    protected $guarded = [];
    public function journal_entry()
    {
        return $this->belongsToMany(JournalEntry::class);
    }
}
