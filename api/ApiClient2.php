<?php
namespace Api;

class ApiClient2 {

    protected $httpClient;

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * GET
     */
    public function getPost(int $postID)
    {
        return $this->httpClient->get('posts/' . $postID);
    }

    /**
     * POST/CREATE
     */
    public function addPost($post)
    {
        $post = [
            'json' =>  $post  
        ];
        return $this->httpClient->post('posts', $post);
    }

    /**
     * DELETE
     */
    public function deletePost($id)
    {
        return $this->httpClient->delete('posts/' . $id);
    }

    /**
     * Here we update only a part of a post, not the whole post.
     */
    public function updatePost($id, $patch)
    {
        $patch = [
            'json' =>  $patch  
        ];
        return $this->httpClient->patch('posts/' . $id, $patch);
    }

    /**
     * Here we completely replace a post object with a new one.
     */
    public function replacePost($id, $post)
    {
        $post = [
            'json' =>  $post  
        ];
        return $this->httpClient->put('posts/'. $id, $post);
    }
}
