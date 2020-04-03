<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $guarded = [];
    public function journal_entries()
    {
        return $this->belongsTo(JournalEntry::class);
    }
    public function path()
    {
        return route('documents.show', $this);
    }
}
