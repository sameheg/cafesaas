<?php

use Modules\Franchise\Events\TemplateUpdated;

it('matches template updated schema', function () {
    $event = new TemplateUpdated('707', ['price' => 20]);
    expect($event->templateId)->toBe('707');
    expect($event->changes)->toBe(['price' => 20]);
});
