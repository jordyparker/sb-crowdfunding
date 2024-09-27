<?php

namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Donation $donation, protected float $totalCollectedAmount) {}

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
        $campaign = $this->donation->campaign;
        $donator = $this->donation->donator;
        $receiver = $campaign->receiver;

        return (new MailMessage)
            ->subject('New donation received 🎊')
            ->greeting(sprintf('Hello %s ', $receiver->getName()))
            ->line(sprintf(
                'We are happy to inform you that you have received a new donation for your donation campaign (%s) from %s',
                $campaign->getName(),
                $donator ? $donator->getName() : ' an anonymous user.'
            ))
            ->line(sprintf(
                'Amount received: %s %s',
                $this->donation->currency,
                number_format($this->donation->amount, 2, '.', ',')
            ))
            ->line(sprintf(
                'Total amount collected: %s %s',
                $this->donation->currency,
                number_format($this->totalCollectedAmount, 2, '.', ',')
            ))
            ->action('View Donation', url('/'))
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
