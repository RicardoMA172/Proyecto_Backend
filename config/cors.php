<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:4200', 
        'http://127.0.0.1:4200',
        'https://boisterous-cajeta-e9a57b.netlify.app',],
    
    'allowed_headers' => ['*'],
    // Allow cookies/credentials from the frontend (required for Sanctum SPA auth)
    'supports_credentials' => true,
];
