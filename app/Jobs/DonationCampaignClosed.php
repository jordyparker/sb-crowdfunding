<?php

namespace App\Jobs;

use App\Contracts\CanRaiseFunds;
use App\Models\User;
use App\Notifications\DonationTargetReached;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class DonationCampaignClosed implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private CanRaiseFunds $campaign) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::query()
            ->select('id', 'first_name', 'last_name', 'email')
            ->whereHas('donations', function ($q) {
                $q->where('campaign_id', $this->campaign->id)
                    ->where('campaign_type', get_class($this->campaign));
            })
            ->chunk(1000, function ($users, $count) {
                Notification::send($users, new DonationTargetReached(
                    $this->campaign,
                    now()->addMinutes(($count - 1) * 10)
                ));
            });
    }
}
