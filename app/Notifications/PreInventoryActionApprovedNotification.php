<?php

namespace App\Notifications;

use App\Models\PreInventoryAction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * 预出\入库(入库单\出货单) 审核通过事件通知 -> 通知仓库分配库存
 * Class PreInventoryActionApprovedNotification
 * @package App\Notifications
 */
class PreInventoryActionApprovedNotification extends Notification
{
    use Queueable;

    public $action;

    /**
     * Create a new notification instance.
     *
     * @param PreInventoryAction $action
     */
    public function __construct(PreInventoryAction $action)
    {
        $this->action = $action;
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
            //
        ];
    }
}
