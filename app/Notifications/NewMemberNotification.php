<?php

namespace App\Notifications;

use App\Models\Publisher;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMemberNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Publisher $publisher,
    ) {}

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
            ->subject(__('You have been added as a member to :publisher', ['publisher' => $this->publisher->name]))
            ->line(__('You have been added as a member to :publisher.', ['publisher' => $this->publisher->name]))
            ->action(__('Login'), route('website.login'));
    }
}
