<?php

namespace App\Services\Ext;

use App\Services\Ext\Integrations\GetUserInfoIntegration;

class BnpbExternalService
{
    public function findUserByToken(string $token): array
    {
        $url = (new GetUserInfoIntegration)->getBaseUrl();

        $setBody = (new GetUserInfoIntegration)->setBody();

        return (new CallBnpbApiConnector)->callApi(url: $url, token: $token, data: $setBody->getBody());
    }
}
