<?php

namespace AppBundle\Client;

use GuzzleHttp\Client;

class JsonRatesClient extends Client
{
    /**
     * @const BASE_URL
     */
    const BASE_URL = 'http://jsonrates.com';

    /**
     * Constructor.
     *
     * @param bool $debug
     */
    public function __construct($debug = false)
    {
        parent::__construct([
            'base_uri'   => static::BASE_URL,
            'config'     => [
                'curl' => [
                    CURLOPT_FRESH_CONNECT  => $debug,
                    CURLOPT_FAILONERROR    => $debug,
                    CURLOPT_VERBOSE        => $debug,
                    CURLOPT_RETURNTRANSFER => true,
                ]
            ],
            'verify'     => false,
            'exceptions' => false,
        ]);
    }
}
