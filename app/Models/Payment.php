<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'charge_event_id',
        'payment_intent_id',
        'payment_type',
        'amount',
        'status',
    ];
}
