<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPaymentIntent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_intent_id',
        'payment_intent_secret',
        'payment_capture_id',
        'amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
