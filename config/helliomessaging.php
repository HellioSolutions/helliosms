<?php

return [
    'client_id' => env('HELLIO_MESSAGING_CLIENT_ID'),
    'application_secret' => env('HELLIO_MESSAGING_APPLICATION_SECRET'),
    'default_sender' => env('HELLIO_MESSAGING_DEFAULT_SENDER', env(APP_NAME)), //Please note that the maximum length of the sender is 11 characters.
];
