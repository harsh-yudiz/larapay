<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'price_id',
        'product_price',
        'product_name',
        'description',
        'billing_period',
        'is_product',
        'is_plan',
        'plan_id'
    ];

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'plan_id', 'id');
    }
}
