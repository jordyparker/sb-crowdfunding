<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payer_id',
        'payer_type',
        'item_type',
        'item_id',
        'base_amount',
        'amount',
        'base_currency',
        'currency',
        'external_id',
        'transaction_id',
        'payment_method',
        'status',
        'payment_number'
    ];

    public function payer()
    {
        return $this->morphTo();
    }

    public function item()
    {
        return $this->morphTo();
    }
}
