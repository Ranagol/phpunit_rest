<?php

use GuzzleHttp\Client;
use Api\ApiClient;
use GuzzleHttp\Handler\MockHandler;//I guess this is a Guzzle mock class, provided by Guzzle...?
use GuzzleHttp\Psr7\Response;

/**
 * Our app for testing will work with https://api.postcodes.io/. This is where we want to send 
 * requests. Not this page works with UK postcodes. Here are a few examples:
 * OX49 5NU: This postcode is in Oxfordshire, England
 * M32 0JG: This postcode is in Manchester, England.
 * NE30 1DP: This postcode is in North Tyneside, England.
 * 
 */
class ApiClientTest extends PHPUnit\Framework\TestCase {

    /**
     * This is Guzzle. But we will add to it a part (the $mockHandler), and $mockHandler will give 
     * the ability to Guzzle to not to call real api.
     */
    protected $httpClient;
    protected $apiClient;//this is our app, our class that we want to test
    protected $mockHandler;//This is a mock thingy that will be put into Guzzle object

    /**
     * This will be triggered before each test.
     */
    public function setUp(): void
    {
        $this->mockHandler = new MockHandler();

        /**
         * When we instantiate Guzzle Client like this, it will not do real life api calls. Here we
         * literally add a new part into an already existing Guzzle Client. This new part has the
         * api mocking ability. 
         */
        $this->httpClient = new Client([
            'handler' => $this->mockHandler,
        ]);
        $this->apiClient = new ApiClient($this->httpClient);
    }

    /**
     * This will be triggered after each test.
     */
    public function tearDown(): void
    {
        $this->httpClient = null;
        $this->apiClient = null;

    }

    /**
     * This is how our final url will look like:
     * api.postcodes.io/postcodes/OX49 5NU
     * This is where we send our GET request.
     * 
     */
    public function testShowPostcodeData()
    {
        /**
         * Here we set up how our mock response from the $mockHandler should look.
         */
        $this->mockHandler->append(new Response(
            200,//Here we set the response status
            [], 
            //this is the file that contains a full response from https://api.postcodes.io/
            file_get_contents(__DIR__ . '/fixtures/postcode.json')
        ));

        /**
         * So. We trigger here the $apiClient, which is our app, our class. The $apiClient has
         * in it the mocked Guzzle, not the real Guzzle. Therefore no real request will be sent, and
         * the previously prepared response will be received.
         * This is the response from the API endpoint. Here we check if the status code is 200.
         */
        $response = $this->apiClient->getPostcodeData('OX49 5NU');
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('admin_district', $data['result']);
    }

    /**
     * In this request we ask for multiple post code data. That is the reason why we send
     * 'postcodes' => ["OX49 5NU", "M32 0JG", "NE30 1DP"]. 
     * 
     */
    public function testShowPostcodesData()
    {
        //All same as in the previous
        $this->mockHandler->append(new Response(
            200, 
            [], 
            file_get_contents(__DIR__ . '/fixtures/postcodes.json')
        ));

        $response = $this->apiClient->getPostcodesData(["OX49 5NU", "M32 0JG", "NE30 1DP"]);

        $data = json_decode($response->getBody(), true);

        /**
         * Since we are asking data for 3 postcode from the api, the $data['result'] part of
         * the response, which is an array, should contain 3 subarrays, with the requested data for
         * each post code. So, here we expect to have 3 subarrays.
         */
        $this->assertCount(3, $data['result']);
    }
}
