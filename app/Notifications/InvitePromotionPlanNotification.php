<?php

namespace App\Notifications;

use App\Models\DP\PromotionPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvitePromotionPlanNotification extends Notification
{
    use Queueable;

    public static $typeName = '促销活动邀请';

    public $plan;
    public $title;
    public $body;

    /**
     * Create a new notification instance.
     *
     * @param PromotionPlan $plan
     * @param $title
     * @param $body
     */
    public function __construct(PromotionPlan $plan, $title, $body)
    {
        $this->plan = $plan;
        $this->title = $title;
        $this->body = $body;
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
        $this->plan->loadMissing(['supplier', 'promotion', 'promotionVariants.variant.dpPrice']);
        return [
            'user' => auth()->user(),
            'title' => $this->title,
            'body' => $this->body,
            'plan' => $this->plan,
        ];
    }
}
