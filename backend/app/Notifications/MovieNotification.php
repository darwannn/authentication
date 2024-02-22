<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MovieNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $id,)
    {
        $this->title = $title;
        $this->message = $message;
        $this->id = $id;
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
            ->greeting('Greetings!')
            ->from('darwinsanluis.ramos14@gmail.com', 'Darw In')
            ->subject('Invoice Payment Failed')
            ->line('1st line')
            ->action(' Action', url('/' . $this->id))
            ->line('2nd line');
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
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message
        ];
    }
}
