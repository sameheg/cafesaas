<?php

return [
    'ip_allowlist' => array_filter(explode(',', env('IP_ALLOWLIST', ''))),
    'throttle_per_minute' => env('THROTTLE_PER_MINUTE', 60),
];
