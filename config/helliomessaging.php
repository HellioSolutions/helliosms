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

    'apiVersion' => 'v1',

];
