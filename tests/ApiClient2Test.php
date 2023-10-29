<?php
use GuzzleHttp\Client;
use Api\ApiClient2;


class ApiClient2Test extends  PHPUnit\Framework\TestCase {

    protected $httpClient;
    protected $apiClient;

    /**
     * Runs before every test
     */
    public function setUp(): void
    {
        $this->httpClient = new Client(['base_uri' => 'http://localhost:3000/']);
        $this->apiClient = new ApiClient2($this->httpClient);
    }

    /**
     * Runs after every test.
     */
    public function tearDown(): void
    {
        $this->httpClient = null;
        $this->apiClient = null;
    }

    /**
     * GET
     */
    public function testShowPost()
    {
        $response = $this->apiClient->getPost(1);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('title', $data);
    }

    /**
     * POST
     */
    public function testAddPost()
    {
        //Here we want to create a new post.
        $this->apiClient->addPost(
            [
                'id'=>2,
                'title'=>'title2',
                'author'=>'author2'
            ]
        );

        //Here we want to get beck from the api the just created post.
        $response = $this->apiClient->getPost(2);
        $data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('title', $data);
        $this->assertEquals($data['title'], 'title2');
    }

    /**
     * @depends testAddPost
     * DELETE
     * This is an annotation.
     * Yes, this function depends on testAddPost() because we want to delete exactly
     * post with id = 2, which is create by testAddPost().
     */
    public function testDeletePost()
    {
        $response = $this->apiClient->deletePost(2);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * PATCH OR PUT
     * Here we update only a part of a post, not the whole post.
     */
    public function testUpdatePost()
    {
        //Updating the post
        $this->apiClient->updatePost(
            1, 
            ['title'=>'json-server2']
        );

        //Getting the newly updated post for testing
        $response = $this->apiClient->getPost(1);
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('title', $data);
        $this->assertEquals($data['title'], 'json-server2');
    }

    /**
     * PATCH OR PUT
     * Here we completely replace a post object with a new one.
     */
    public function testReplacePost()
    {
        //Replacing post id = 1 with a completely new pobject
        $this->apiClient->replacePost(
            1, 
            [
                'id'=>1,
                'title'=>'title2',
                'author'=>'author2'
            ]
        );

        //Getting from api the new object, for testing.
        $response = $this->apiClient->getPost(1);
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('title', $data);
        $this->assertEquals($data['title'], 'title2');
    }

}
