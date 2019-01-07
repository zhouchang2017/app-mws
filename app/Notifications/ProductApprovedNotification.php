<?php

namespace App\Notifications;

use App\Models\DP\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/*
 * 产品审核通过消息通知
 * */

class ProductApprovedNotification extends Notification
{
    use Queueable;

    public $product;

    public static $typeName = '产品审核通过';

    /**
     * Create a new notification instance.
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
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
        $supplier = $this->product->suppliers()->first();
        return [
            'supplier_id' => $supplier->id, //供应商id
            'supplier_name' => $supplier->name, //供应商名称
            'product_id' => $this->product->id, // 产品id
            'product_name' => $this->product->name, // 产品名称
            'taxon_id' => $this->product->taxon->id, // 分类id
            'taxon_name' => $this->product->taxon->name, // 分类名称
            'user' => auth()->user(), // 审核人
        ];
    }
}
