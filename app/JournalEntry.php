<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $guarded = [];
    public function postings()
    {
        return $this->belongsToMany(Posting::class);
    }
    public function post($posting)
    {
        if (is_string($posting)) {
            $posting = Posting::whereName($posting)->firstOrFail();
        }
        $this->postings()->sync($posting, false);
    }
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_type_id');
    }
    public function path()
    {
        return route('journal_entries.show', $this);
    }
}
