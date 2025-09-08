<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderDeliveredNotification extends Notification
{
    use Queueable;

    public function __construct(private Order $order, private array $channels) {}

    public function via(object $notifiable): array
    {
        return $this->channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Delivered')
            ->line('Order #'.$this->order->id.' has been delivered.');
    }

    public function toSms(object $notifiable): string
    {
        return 'Order #'.$this->order->id.' has been delivered.';
    }
}
