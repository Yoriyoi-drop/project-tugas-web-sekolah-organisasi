<?php

namespace Tests\Traits;

use Illuminate\Support\Str;

trait WithCsrfToken
{
    protected function withCsrfToken()
    {
        // Generate a fake CSRF token
        $token = Str::random(40);
        
        // Add it to the session
        $this->withSession(['_token' => $token]);
        
        return $this;
    }
    
    protected function postWithCsrf($uri, array $data = [])
    {
        $data['_token'] = csrf_token();
        return $this->post($uri, $data);
    }
    
    protected function putWithCsrf($uri, array $data = [])
    {
        $data['_token'] = csrf_token();
        return $this->put($uri, $data);
    }
    
    protected function deleteWithCsrf($uri, array $data = [])
    {
        $data['_token'] = csrf_token();
        return $this->delete($uri, $data);
    }
}