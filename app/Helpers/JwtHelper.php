<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    protected $key;

    protected $method;

    protected $token;

    public function getKey()
    {
        return $this->key;
    }

    public function setKey(string $key)
    {
        $this->key = $key;

        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod(string $method)
    {
        $this->method = $method;

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken(string $token)
    {
        $this->token = $token;

        return $this;
    }

    public static function decodeToken(string $token, string $key, string $method)
    {
        JWT::$leeway += 20;
        do {
            $attempt = 0;
            try {
                $keyJwt = new Key($key, $method);
                $payload = JWT::decode($token, $keyJwt);
                $retry = false;

                return $payload;
            } catch (\Firebase\JWT\BeforeValidException $e) {
                $attempt++;
                $retry = $attempt < 2;
            } catch (\Firebase\JWT\ExpiredException $e) {
                $retry = false;
                throw $e;
            } catch (\Exception $e) {
                throw $e;
            }
        } while ($retry);
    }

    public static function checkValidTokenWithExp(string $token, string $key, string $method)
    {
        $payload = (new JwtHelper)->decodeToken($token, $key, $method);
        if (isset($payload)) {
            if (! empty($payload)) {
                if (is_object($payload)) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function checkValidToken(string $token, string $key, string $method)
    {
        $payload = (new JwtHelper)->decodeToken($token, $key, $method);
        if (isset($payload)) {
            if (! empty($payload)) {
                if (is_object($payload)) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function decodeAuthJwt()
    {
        $cookieName = config('app.jwt_cookie_name');
        $accessToken = $_COOKIE[$cookieName];
        $key = config('services.auth_key.jwt_secret');
        $method = config('services.auth_key.jwt_method');
        $jwtData = self::decodeToken($accessToken, $key, $method);

        return $jwtData;
    }
}
