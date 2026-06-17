<?php

return [
    'batch_size' => env('MESSAGE_BATCH_SIZE', 2),
    'interval_seconds' => env('MESSAGE_INTERVAL_SECONDS', 5),
    'content_max_length' => env('MESSAGE_CONTENT_MAX_LENGTH', 160),
    'cache_ttl_days' => env('MESSAGE_CACHE_TTL_DAYS', 7),
];