<?php
namespace Diagro\Token;

use Exception;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

abstract class Token
{


    /**
     * @var string The frontend application id
     */
    private string $issuer;

    /**
     * @var string The browser/os or the mobiele device description
     */
    private string $device;

    /**
     * @var string|null Cache generated token or the token which is generated from
     */
    public ?string $generated_token;


    /**
     * Token constructor.
     *
     * @param string $issuer
     * @param string $device
     */
    public function __construct(string $issuer, string $device)
    {
        $this->issuer = $issuer;
        $this->device = $device;
    }


    /**
     * @return string
     */
    public function issuer() : string
    {
        return $this->issuer;
    }


    /**
     * @return string
     */
    public function device() : string
    {
        return $this->device;
    }


    /**
     * Create the token based on the properties.
     *
     * @return string
     * @throws Exception If private key failed
     */
    protected function createToken(): string
    {
        $payload = $this->payload();
        $payload['exp'] = time() + $this->lifetime();
        $payload['iss'] = $this->issuer();
        $payload['device'] = $this->device();

        return JWT::encode($payload, self::getPrivateKey(), 'RS256');
    }

    private static function getJwksUri(): string
    {
        $jwksUri = config('diagro.service_jwks_uri');
        if(! str_ends_with($jwksUri, '/')) {
            $jwksUri .= '/';
        }

        return $jwksUri;
    }


    private static function getPrivateKey(): String
    {
        $jwksUri = self::getJwksUri();
        $response = Http::get($jwksUri . '/x509pem');
        if($response->ok()) {
            return $response->body();
        } else {
            throw new Exception("HTTP status: {$response->status()}, Body: {$response->body()}");
        }
    }

    private static function getPublicKey(): Key
    {
        if(Cache::has('jwks-diagro-1')) {
            return Cache::get('jwks-diagro-1');
        }

        $jwksUri = self::getJwksUri();
        $response = Http::get($jwksUri . '/jwks');
        if($response->ok()) {
            $key = JWK::parseKeySet($response->json())['diagro-1'];
            Cache::put('jwks-diagro-1', $key, 86400); //one day

            return $key;
        } else {
            throw new Exception("HTTP status: {$response->status()}, Body: {$response->body()}");
        }
    }


    /**
     * Create token object from token string.
     *
     * @param string $token
     * @return Token
     * @throws Exception If private key failed
     */
    public static function createFromToken(string $token) : static
    {
        $decoded = JWT::decode($token, self::getPublicKey());

        $obj = static::decode($decoded);
        $obj->generated_token = $token;
        return $obj;
    }


    /**
     * The payload for the token.
     *
     * @return array
     */
    abstract protected function payload() : array;


    /**
     * Make the token class object from the decoded data.
     *
     * @param object $decoded
     * @return Token
     */
    abstract protected static function decode(object $decoded) : static;


    /**
     * The lifespan of this token in seconds.
     *
     * @return int
     */
    abstract protected function lifetime() : int;


}