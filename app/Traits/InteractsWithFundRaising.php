<?php

namespace App\Traits;

use App\Contracts\CanRaiseFunds;
use App\Jobs\DonationCampaignClosed;
use App\Models\Donation;
use App\Models\Transaction;
use App\Notifications\DonationReceived;
use Exception;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

trait InteractsWithFundRaising {
    /**
     * Collect funds for this donation campaign
     * @param Transaction $trannsaction
     * @param string|null $comments
     * @return Donation
     */
    public function collectDonation(Transaction $transaction, string $comments = null): Donation
    {
        if (! $this instanceof CanRaiseFunds) {
            throw new Exception(sprintf('The class %s must implement the interface %s', get_class($this), CanRaiseFunds::class));
        }

        if (! $this->canReceiveDonations()) {
            throw ValidationException::withMessages([
                'campaign' => sprintf('%s cannot receive donations anymore.', $this->getName())
            ]);
        }

        if ($this->getMinimumAmount() !== null) {
            $convertedAmount = convertAmount(
                $transaction->currency,
                $transaction->base_currency,
                $transaction->amount
            );

            if ($convertedAmount < $this->getMinimumAmount()) {
                throw ValidationException::withMessages([
                    'amount' => [(
                        sprintf('Minimum amount set for this donation is %s %s.',
                        convertAmount(
                            $transaction->base_currency,
                            $transaction->currency,
                            $this->getMinimumAmount(),
                        ),
                        $transaction->currency
                    ))],
                ]);
            }
        }

        $donator = request()->user(appGuard());
        $collectedAmount = $this->donations()->sum('amount');

        $donation = $this->donations()->create([
            'donator_id' => $donator ?
                $donator->getKey() : null,
            'donator_type' => $donator ?
                $donator->getMorphClass() : null,
            'running_amount' => $collectedAmount ?: 0,
            'amount' => $transaction->base_amount,
            'currency' => $transaction->base_currency,
            'transaction_id' => $transaction->id,
            'comments' => $comments
        ]);

        $collectedAmount += $donation->amount;

        Notification::send($this->receiver, new DonationReceived($donation, $collectedAmount));

        if ($collectedAmount >= $this->getTargetAmount()) {
            $this->closeCampaign();

            dispatch(new DonationCampaignClosed($this));
        }

        return $donation;
    }

    /**
     * Get all the donations the campaign received
     *
     * @return MorphMany
     */
    public function donations(): MorphMany
    {
        return $this->morphMany(Donation::class, 'campaign');
    }
}
