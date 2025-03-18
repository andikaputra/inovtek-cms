<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterQuizNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private $registrant)
    {
        //
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
        $title = 'Informasi Registrasi Kuis Baru';
        $description = ucwords(strtolower($this->registrant?->name)).' melakukan pendaftaran Kuis '.$this->registrant?->quizLink->name.',mohon untuk dicek';

        return [
            'type' => 'info',
            'title' => $title,
            'description' => $description,
            'url' => route('admin.home.detail.kuis.registrant.index', ['id_provinsi' => $this->registrant?->regionDetail?->region_id, 'id' => $this->registrant?->quiz_link_id]),
            'meta' => [
                'registrant' => $this->registrant,
                'is_read' => false,
            ],
        ];
    }
}
