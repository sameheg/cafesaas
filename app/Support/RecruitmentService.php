<?php

namespace App\Support;

use App\Events\DomainEvent;
use App\Models\Cv;
use App\Models\JobPosting;

class RecruitmentService
{
    public function __construct(private EventBus $bus) {}

    public function publish(JobPosting $job): void
    {
        $job->update(['status' => 'published', 'posted_at' => now()]);
        $this->bus->publish(DomainEvent::JOB_POSTED->value, ['job_id' => $job->id]);
    }

    public function accept(Cv $cv): void
    {
        $cv->update(['status' => 'accepted']);
        $this->bus->publish(DomainEvent::CANDIDATE_ACCEPTED->value, [$cv]);
    }
}
