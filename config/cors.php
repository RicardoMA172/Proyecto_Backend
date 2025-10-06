<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:4200', 
        'http://127.0.0.1:4200',
        'https://boisterous-cajeta-e9a57b.netlify.app',],
    
    'allowed_headers' => ['*'],
    'supports_credentials' => false,
];
