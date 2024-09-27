<?php

namespace App\Models;

use App\Contracts\CanRaiseFunds;
use App\Traits\InteractsWithFundRaising;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DonationCampaign extends Model implements CanRaiseFunds
{
    use HasFactory;
    use InteractsWithFundRaising;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'receiver_id',
        'receiver_type',
        'creator_id',
        'creator_type',
        'slug',
        'name',
        'description',
        'image',
        'target_amount',
        'currency',
        'category_id',
        'min_donation_amount',
        'number_of_participants',
        'ends_at',
        'closed_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ends_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    /**
     * Get the receiver of the donation campaign
     *
     * @return MorphTo
     */
    public function receiver(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the creator of the campaign
     *
     * @return MorphTo
     */
    public function creator(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the category of the campaign
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the name of the crowdfunding
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the target amount of the crowdfunding
     *
     * @return float
     */
    public function getTargetAmount(): float
    {
        return $this->target_amount;
    }

    /**
     * Get the minimum amount to be collected per donation
     *
     * @return ?float
     */
    public function getMinimumAmount(): ?float
    {
        return $this->min_donation_amount;
    }

    /**
     * Get the target currency of the crowdfunding
     *
     * @return string
     */
    public function getTargetCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Check if the crowdfunding can still receive donations
     *
     * @return bool
     */
    public function canReceiveDonations(): bool
    {
        return is_null($this->closed_at) && (!$this->ends_at || !$this->ends_at->isPast());
    }

    /**
     * Check if the crowdfunding is closed
     *
     * @return bool
     */
    public function isClosed(): bool
    {
        return ! is_null($this->closed_at);
    }

    /**
     * Close campaign to prevent it from receiving donations again
     *
     * @return void
     */
    public function closeCampaign(): void
    {
        $this->update(['closed_at' => now()]);
    }
}
