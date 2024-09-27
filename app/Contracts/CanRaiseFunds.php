<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface CanRaiseFunds {
    /**
     * Get the name of the crowdfunding
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the target amount of the crowdfunding
     *
     * @return float
     */
    public function getTargetAmount(): float;

    /**
     * Get the minimum amount to be collected per donation
     *
     * @return ?float
     */
    public function getMinimumAmount(): ?float;

    /**
     * Get the target currency of the crowdfunding
     *
     * @return string
     */
    public function getTargetCurrency(): string;

    /**
     * Check if the crowdfunding can still receive donations
     *
     * @return bool
     */
    public function canReceiveDonations(): bool;

    /**
     * Check if the crowdfunding is closed
     *
     * @return bool
     */
    public function isClosed(): bool;

    /**
     * Close campaign to prevent it from receiving donations again
     *
     * @return void
     */
    public function closeCampaign(): void;

    /**
     * Get the receiver of the donation campaign
     *
     * @return MorphTo
     */
    public function receiver(): MorphTo;

    /**
     * Get all the donations the campaign received
     *
     * @return MorphMany
     */
    public function donations(): MorphMany;

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey();

    /**
     * Get the class name for polymorphic relations.
     *
     * @return string
     */
    public function getMorphClass();
}
