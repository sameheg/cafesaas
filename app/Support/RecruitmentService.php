<?php

namespace App\Support;

use App\Models\Cv;
use App\Models\JobPosting;

class RecruitmentService
{
    public function __construct(private EventBus $bus) {}

    public function publish(JobPosting $job): void
    {
        $job->update(['status' => 'published', 'posted_at' => now()]);
        $this->bus->publish('job.posted', ['job_id' => $job->id]);
    }

    public function accept(Cv $cv): void
    {
        $cv->update(['status' => 'accepted']);
        $this->bus->publish('candidate.accepted', [$cv]);
    }
}
