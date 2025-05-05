<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Email extends Model
{
    protected $fillable = [
        'user_id',
        'subject',
        'body',
        'recipients',
        'status',
        'scheduled_at',
        'attachment_path', // ← pridaj toto
    ];

    protected $casts = [
        'recipients'   => 'array',
        'scheduled_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
