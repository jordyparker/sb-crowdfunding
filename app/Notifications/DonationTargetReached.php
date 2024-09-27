<?php

namespace App\Notifications;

use App\Contracts\CanRaiseFunds;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonationTargetReached extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private CanRaiseFunds $campaign, Carbon $delay)
    {
        $this->delay = $delay;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Donation target reached 🎊')
            ->greeting(sprintf('Hello %s ', $notifiable->getName()))
            ->line(sprintf(
                'We are pleased to inform you that the donation campaign (%s) in which you participated has reached its objective. We thank you for your participation on behalf of the campaign owner.',
                $this->campaign->getName()
            ))
            ->action('View Donations', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
