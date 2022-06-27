<?php

use App\Http\Controllers\GuardianController;

return [
    'keys' => [
        'GUARDIAN_KEY' => env('GUARDIAN_KEY', false)
    ],
    'providers' => [
        GuardianController::class
    ]
];
