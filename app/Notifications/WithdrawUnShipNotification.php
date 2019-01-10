<?php

namespace App\Notifications;

use App\Models\Withdraw;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WithdrawUnShipNotification extends Notification
{
    use Queueable;

    public static $typeName = '等待仓库中心发货';

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
            'description' => $this->withdraw->description, // 描述
            'supply_id' => $this->withdraw->id, // 申请入库计划id
            'supplier_name' => $this->withdraw->origin->name, // 供应商名称
            'user_name' => $this->withdraw->latestStatus(Withdraw::UN_SHIP)->user->name,
            'created_at' => $this->withdraw->latestStatus(Withdraw::UN_SHIP)->created_at,
        ];
    }
}
