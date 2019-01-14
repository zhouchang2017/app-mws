<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/14
 * Time: 2:54 PM
 */

namespace App\Channels;
use Illuminate\Notifications\Notification;


class WechatPushChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toVoice($notifiable);

        // Send notification to the $notifiable instance...
    }
}