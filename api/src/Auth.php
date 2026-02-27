<?php
namespace App;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth
{
    private $secret;
    private $issuer;
    private $aud;

    public function __construct(array $cfg)
    {
        $this->secret = $cfg['secret'];
        $this->issuer = $cfg['issuer'];
        $this->aud = $cfg['aud'];
    }

    public function issueToken(array $payload, int $ttl = 3600): string
    {
        $now = time();
        $token = array_merge(["iss" => $this->issuer, "aud" => $this->aud, "iat" => $now, "nbf" => $now, "exp" => $now + $ttl], $payload);
        return JWT::encode($token, $this->secret, 'HS256');
    }

    public function validateToken(string $jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secret, 'HS256'));
            return (array)$decoded;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
