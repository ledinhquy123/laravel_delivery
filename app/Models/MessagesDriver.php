<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessagesDriver extends Model
{
    use HasFactory;
    protected $table = 'messages_drivers';

    protected $appends = ['sender_id'];
    protected $senderIdValue;

    public function getSenderIdAttribute()
    {
        return $this->senderIdValue;
    }

    public function withSenderIdAttribute($value)
    {
        $this->senderIdValue = $value;
        return $this;
    }
    
    protected $fillable = [
        'message',
        'order_id'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }
}