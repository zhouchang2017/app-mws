<?php

namespace App\Notifications;

use App\Models\Withdraw;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WithdrawApprovedNotification extends Notification
{
    use Queueable;


    public static $typeName = '退仓服务审核通过';

    public $withdraw;

    /**
     * Create a new notification instance.
     *
     * @param Withdraw $withdraw
     */
    public function __construct(Withdraw $withdraw)
    {
        $this->withdraw = $withdraw;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'description' => $this->withdraw->preInventoryAction->description,
            'supply_id' => $this->withdraw->id,
            'supplier_id' => $this->withdraw->origin->id,
            'supplier_name' => $this->withdraw->origin->name,
            'supplier_user_name' => $this->withdraw->latestStatus(Withdraw::PENDING)->user->name,
            'user_id' => $this->withdraw->latestStatus(Withdraw::APPROVED)->user->id,
            'user_name' => $this->withdraw->latestStatus(Withdraw::APPROVED)->user->name,
            'created_at' => $this->withdraw->latestStatus(Withdraw::PENDING)->created_at,
        ];
    }
}
