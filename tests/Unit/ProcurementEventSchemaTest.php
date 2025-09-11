<?php

use Modules\Procurement\Events\PoCreated;

it('formats po created event', function () {
    $event = new PoCreated('101', 'sup1');
    expect($event->toArray())->toBe([
        'event' => 'procurement.po.created',
        'data' => ['po_id' => '101', 'supplier_id' => 'sup1'],
    ]);
});
