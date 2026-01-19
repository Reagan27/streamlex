<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['support_issue_id', 'user_id', 'comment'];

    public function supportIssue()
    {
        return $this->belongsTo(SupportIssue::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
