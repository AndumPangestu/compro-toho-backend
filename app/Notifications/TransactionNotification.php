<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;


class TransactionNotification extends Notification implements ShouldQueue
{
    use Queueable, InteractsWithQueue;


    private $transaction;
    /**
     * Create a new notification instance.
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $message = "Dana masuk untuk donasi " . $this->transaction->donation->title . " sejumlah Rp " . number_format($this->transaction->amount, 0, ',', '.') . " telah diterima.";

        return [
            'type' => 'transaction',
            'transaction_id' => $this->transaction->id,
            'transaction_status' => $this->transaction->transaction_status,
            'updated_at' => now(),
            'message' => $message,
        ];
    }
}
