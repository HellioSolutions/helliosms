<?php

return [

    /**
     * Live API url
     *
     */
    'baseUrl' => 'https://api.helliomessaging.com/',

    /**
     * Client Id
     *
     */
    'clientId' => getenv('HELLIO_MESSAGING_CLIENT_ID'),

    /**
     * Application Secret
     *
     */
    'applicationSecret' => getenv('HELLIO_MESSAGING_APPLICATION_SECRET'),


    /**
     * Default Sender Id
     *
     */
    'defaultSender' => getenv('HELLIO_MESSAGING_DEFAULT_SENDER'),


    /**
     * Default API version
     *
     */

    'apiVersion' => getenv('HELLIO_MESSAGING_API_VERSION'),

    /**
     * Default username
     *
     */
    'username' => getenv('HELLIO_MESSAGING_USERNAME'),


    /**
     * Default password
     *
     */

    'password' => getenv('HELLIO_MESSAGING_PASSWORD'),

];
