<?php

namespace App\Services\Ext;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class CallBnpbApiConnector
{
    public function callApi(string $url, string $token, array $data = [], string $type = 'default'): array|Exception
    {
        switch ($type) {
            case 'default':
                return $this->_defaultApi(url: $url, token: $token, queryParams: isset($data['queryParams']) ? $data['queryParams'] : []);
                break;

            default:
                throw new Exception(trans('response.server.integration', ['integration' => 'BNPB Auth Login', 'error' => 'Invalid Type']), Response::HTTP_BAD_REQUEST);
                break;
        }
    }

    public function _defaultApi(string $url, string $token, array $queryParams = []): array
    {
        try {
            $client = new Client;

            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ];

            $options = [
                'headers' => $headers,
            ];

            if (! empty($queryParams)) {
                $options['query'] = $queryParams;
            }

            $response = $client->request('GET', $url, $options);
            $body = json_decode($response->getBody(), true);

            if (! isset($body) || $body['success'] !== true) {
                Log::error(__METHOD__, ['error' => 'Empty body response or error response', $body]);

                return [
                    'responseCode' => Response::HTTP_NO_CONTENT,
                    'responseDesc' => 'Empty Body Response',
                ];
            }

            return $body['data'];
        } catch (Throwable $th) {
            Log::error(__METHOD__, ['error' => $th->getMessage()]);

            dd($th);
            if ($th->getCode() == Response::HTTP_UNAUTHORIZED) {
            }

            return [
                'responseCode' => $th->getCode(),
                'responseDesc' => $th->getMessage(),
            ];
        }
    }
}
