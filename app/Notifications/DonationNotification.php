<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DonationNotification extends Notification implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public $donation;
    /**
     * Create a new notification instance.
     */
    public function __construct($donation)
    {
        $this->donation = $donation;
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
        $donationUrl = config('app.font_end_url') . "/program/" . $this->donation->slug;

        return (new MailMessage)
            ->subject('New Donation Update')
            ->view('emails.donation-notification', ['donationUrl' => $donationUrl, 'donation' => $this->donation]);
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
