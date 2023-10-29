<?php

namespace Api;

/**
 * This is the class that sends request to an api, with Guzzle.
 * Our app for testing will work with https://api.postcodes.io/. This is where we want to send 
 * requests. Not this page works with UK postcodes. Here are a few examples:
 * OX49 5NU: This postcode is in Oxfordshire, England
 * M32 0JG: This postcode is in Manchester, England.
 * NE30 1DP: This postcode is in North Tyneside, England.
 */
class ApiClient {

    /**
     * The Guzzle object is stored here.
     */
    protected $httpClient;

    /**
     * Here we receive a Guzzle client already instantiated.
     */
    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Getting 1 postcode
     */
    public function getPostcodeData(string $postcode)
    {
        return $this->httpClient->get('postcodes/'.$postcode);
    }

    /**
     * Getting multiple postcodes.
     */
    public function getPostcodesData(array $postcodes)
    {
        $postcodes = [
            'json' => [
                'postcodes' => $postcodes
            ]
        ];
        return $this->httpClient->post('/postcodes', $postcodes);
    }
}
