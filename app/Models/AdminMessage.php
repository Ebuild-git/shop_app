<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminMessage extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'sujet',
        'message',
        'post_id',
        'sent_from',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id')->withTrashed();
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'to_user_id')->withTrashed();
    }

    public function post()
    {
        return $this->belongsTo(posts::class, 'post_id')->withTrashed();
    }
}
