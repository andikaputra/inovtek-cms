<?php

namespace App\Services\Ext\Integrations;

class GetUserInfoIntegration
{
    private $baseUrl;

    private $body;

    private $path = 'api/profile';

    public function __construct()
    {
        $this->baseUrl = config('services.bnpb.login_url').'/'.$this->path;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setBody(): ?GetUserInfoIntegration
    {
        $body = [];

        $this->body = $body;

        return $this;
    }

    public function getBody(): ?array
    {
        return $this->body;
    }
}
