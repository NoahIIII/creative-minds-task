<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationSender extends Notification
{
    use Queueable;
    private $databaseData;

    /**
     * Create a new notification instance.
     */
    public function __construct($notificationData)
    {
        if (isset($notificationData['databaseData'])) {
            $this->databaseData = $notificationData['databaseData'];
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $sendTo = [];

        // sent to mail
        if (!is_null($this->databaseData)) {
            $sendTo[] = 'database';
        }

        return $sendTo;
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
    public function toDatabase(object $notifiable)
    {
        return $this->databaseData;
    }
}
