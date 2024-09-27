<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Donation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'donator_id',
        'donator_type',
        'campaign_id',
        'campaign_type',
        'running_amount',
        'amount',
        'currency',
        'transaction_id',
        'comments'
    ];

    /**
     * Get the donator of the donation
     *
     * @return MorphTo
     */
    public function donator(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the campaign that received the donation
     *
     * @return MorphTo
     */
    public function campaign(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Returns the transaction of this donation
     * @return BelongsTo
     */
    public function transaction() : BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
