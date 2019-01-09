<?php

namespace App\Notifications;

use App\Models\DP\PromotionPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmPromotionPlanNotification extends Notification
{
    use Queueable;

    public static $typeName = '接收促销计划邀请';

    public $plan;

    /**
     * Create a new notification instance.
     *
     * @param PromotionPlan $plan
     */
    public function __construct(PromotionPlan $plan)
    {
        $this->plan = $plan;
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
        $this->plan->loadMissing(['supplier', 'promotion', 'promotionVariants.variant.dpPrice']);
        return [
            'user' => auth()->user(),
            'plan' => $this->plan,
        ];
    }
}
