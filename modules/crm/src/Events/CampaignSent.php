<?php

declare(strict_types=1);

namespace Modules\Crm\Events;

class CampaignSent
{
    public function __construct(public string $campaignId, public string $segment)
    {
    }

    public function toArray(): array
    {
        return [
            'event' => 'crm.campaign.sent',
            'data' => [
                'campaign_id' => $this->campaignId,
                'segment' => $this->segment,
            ],
        ];
    }
}
