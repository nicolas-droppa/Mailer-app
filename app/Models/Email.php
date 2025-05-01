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
    ];

    protected $casts = [
        'recipients' => 'array',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
