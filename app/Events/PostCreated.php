<?php
namespace App\Events;

class PostCreated
{
    public $post;
    public function __construct($post = null)
    {
        $this->post = $post;
    }
}
