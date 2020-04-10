<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $guarded = [];
    public function journal_entries()
    {
        return $this->hasMany(JournalEntry::class, 'document_type_id');
    }
    public function path()
    {
        return route('documents.show', $this);
    }
}
