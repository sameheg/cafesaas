<?php

declare(strict_types=1);

namespace Modules\Crm\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Crm\Events\CampaignSent;
use Modules\Crm\Models\Campaign;
use App\Support\EventBus;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Campaign $campaign)
    {
    }

    public function handle(EventBus $bus): void
    {
        $campaign = $this->campaign->fresh('segment');
        $campaign->status = \Modules\Crm\Models\CampaignStatus::Sent;
        $campaign->save();

        $event = new CampaignSent($campaign->id, $campaign->segment->name);
        $bus->publish($event->toArray()['event'].'@v1', $event->toArray()['data']);
    }
}
