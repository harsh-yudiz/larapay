<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'subscription_id',
        'schedule_subscription_id',
        'scheduled_period_start',
        'current_period_start',
        'current_period_end',
        'stripe_customer_id',
        'status',
        'is_subscription'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'plan_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);   
    }
}
