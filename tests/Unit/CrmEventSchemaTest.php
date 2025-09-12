<?php

declare(strict_types=1);

use Modules\Crm\Events\CampaignSent;

it('formats campaign sent event', function (): void {
    $event = new CampaignSent('303', 'high_value');
    expect($event->toArray())->toBe([
        'event' => 'crm.campaign.sent',
        'data' => ['campaign_id' => '303', 'segment' => 'high_value'],
    ]);
});
