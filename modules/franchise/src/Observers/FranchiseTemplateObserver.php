<?php

namespace Modules\Franchise\Observers;

use Modules\Franchise\Events\TemplateUpdated;
use Modules\Franchise\Models\FranchiseTemplate;

class FranchiseTemplateObserver
{
    public function updated(FranchiseTemplate $template): void
    {
        TemplateUpdated::dispatch(
            $template->getKey(),
            $template->getChanges()
        );
    }
}
