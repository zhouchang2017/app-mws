<?php

namespace App\Notifications;

use App\Models\Supply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SupplyPendingNotification extends Notification
{
    use Queueable;

    public $supply;

    /**
     * Create a new notification instance.
     *
     * @param Supply $supply
     */
    public function __construct(Supply $supply)
    {
        $this->supply = $supply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'description' => $this->supply->description,
            'supply_id' => $this->supply->id,
            'supplier_id' => $this->supply->origin->id,
            'supplier_name' => $this->supply->origin->name,
            'supplier_user_name' => $this->supply->latestStatus(Supply::PENDING)->user->name,
            'created_at' => $this->supply->latestStatus(Supply::PENDING)->created_at,
        ];
    }
}
