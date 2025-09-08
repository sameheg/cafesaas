<?php

namespace App\Jobs;

use App\Events\NotificationSent;
use App\Mail\TemplateMail;
use App\Models\NotificationTemplate;
use App\Services\Integrations\SmsGatewayFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $notifiableType,
        public int $notifiableId,
        public string $templateKey,
        public array $channels = []
    ) {}

    public function handle(): void
    {
        $notifiable = ($this->notifiableType)::find($this->notifiableId);
        if (! $notifiable) {
            return;
        }

        $template = NotificationTemplate::where('tenant_id', $notifiable->tenant_id)
            ->where('key', $this->templateKey)
            ->first();

        if (! $template) {
            return;
        }

        foreach ($this->channels as $channel) {
            switch ($channel) {
                case 'mail':
                    if ($notifiable->email && $template->mail_subject && $template->mail_body) {
                        Mail::to($notifiable->email)
                            ->send(new TemplateMail($template->mail_subject, $template->mail_body));
                    }
                    break;
                case 'sms':
                    if ($notifiable->phone && $template->sms_text) {
                        $gateway = app(SmsGatewayFactory::class)->make('twilio', $notifiable->tenant_id);
                        $gateway->send($notifiable->phone, $template->sms_text);
                    }
                    break;
                case 'push':
                    if ($template->push_body) {
                        Log::info('Push to '.$notifiable->id.': '.$template->push_body);
                    }
                    break;
            }
        }

        event(new NotificationSent($notifiable, $this->templateKey, $this->channels));
    }
}
