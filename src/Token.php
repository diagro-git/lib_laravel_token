<?php
namespace Diagro\Token;

use Exception;
use Firebase\JWT\JWT;
use OpenSSLAsymmetricKey;

abstract class Token
{


    private static $public_key = <<<RSA_PUB_KEY
    -----BEGIN PUBLIC KEY-----
    MIIBojANBgkqhkiG9w0BAQEFAAOCAY8AMIIBigKCAYEAx6rM4vfjSiTkQ0Z61haF
    HlD37jk66m1O6Go9QBcQbyHkwAuxhkyqpfHjrcnCFiUYihl+2ivhrEyvnWeLu+cM
    TgqnKEtFfuQ+pW9H5WLqhMF6epZ088Pe+5pGSPaM00R+CC+oBFdrgWyfJsyqxCYZ
    K+02kV73yzwm4PktkfAweXqwXh0T38Vlj8wFI0eg8vMsZibS3a16uZ0J+/WiClw+
    kBhd7fLuP9mOSqHFsyb8Yu16K3xN64nAK9TzI1WnvNUjQd6cVrnhzvDZNh1IXnIu
    hw2OTShjd5ccX7fvWIYj1YAW8t4LRONV504Qf0dluAimRaXhmUDmoKKcf7Cz4ZTp
    cn4A+vb5bln5pDzM35fzKlCj+a0hPvuS+TRaMi+ci3u+llJ2S6oOctNwqd8OwCmM
    Sxr3o6lX9R14CzEYwODVFSKHVcz1Lx+RY1SXYmTo11NfGecmGy4fMk3SNJsXirgz
    JBCzkeWrmfwI88T+dEUZl0CKp1LIiZWHyR2BfYz/EZMbAgMBAAE=
    -----END PUBLIC KEY-----
    RSA_PUB_KEY;


    private static $private_key = <<<RSA_PRIV_KEY
    -----BEGIN ENCRYPTED PRIVATE KEY-----
    MIIHbTBXBgkqhkiG9w0BBQ0wSjApBgkqhkiG9w0BBQwwHAQI5attHC9pMmQCAggA
    MAwGCCqGSIb3DQIJBQAwHQYJYIZIAWUDBAECBBBHp5w1OhSZxkf1JqM4kpG9BIIH
    EIWf273+IuoFo11fMvO+VSF1nulJXyTiMxlcZYn6NPGf9Hb4jK3iTsHl0pob5mZ3
    v+W1H+mh3Pfb3YXWsvyZr8ImPWVLczpzF5kKUTekjjySudstrwnTKRIs1M46vBEA
    q5VBdukr/gZUtXlFc9DJHBXbMb9HCJ9qov9ED6zUJTIFe+GsicdNozfbJO01r02F
    8/bt0uBZY8TGQmjiEZPWpFJiHowybah1JkgI4BHrHNCkxkwLTqayO5ioPy6PDkYK
    mNPxs/merkuCbKDf85987gj5Yh3mifhi9JS5d+69XdpeepkRp0eKh+fRQfycVHBn
    vkFhzBAjeWuVsdiF1eV1uOpGKQX3NrjRqNDZJ1ZxN27t6rdVL7E9kqDEAwxidi0L
    +JYcQP37rq95aHNwgS7rxHjKU7M1nx2ZBQDmwCaNFZAUNY9lEaoLdEn3ggTXOYBX
    u1Em6WKsEHSatxKO6ZMUiEGanDYP+DOJwRt5oZcc6FbZmhmNF8HYCz68+PnJ11hf
    ava/fMhMbJC4TRnFWkTv2EXjNWjktvC8xOTXsFs4nxJOpVeUtBozjz9LhiFhIUan
    d842Id/geYY/HMQAxrwZuQqUa7AgA7nJnt9rvHjTfJk9K2v3ZRUPH12iQA90QYYc
    DRAb6acpK18oggp/GIMwrNhyhueabpvfJt8YQMSIA3TxJE0qAWdsrWOaBHXKV9gP
    nIhyvHIoUkWvDlnPBquT9CoxSIfHsJR+qrUwBzKMqYxbM3aJKJgE9xcweilt2aPD
    ZkdcGuPznQF6s5Iz2EBN4OOmoomwKq0qa+rtKmsySC38MbhNbhVrR8y7m98HnFlJ
    OxwqJahMc8jWhacxsudPOuuMPf++E45AJXfLxkj8nlgKvvdwbFVMwF06Q4A8u2bM
    2Q89HW/ApzJizrT54FKWVzgBxlN0flEx3jZc6gBab5bFWn2BOKG04nD7G9kxg5By
    x/6RBiUeSV6Cj+5z2Hx7f7m3yMIp8d9yO33QFX/R9JwGYaCjdWMjv3HYSAPzTNyz
    HHsA2NoAL9QK6EG4ixTdjinJqnd8YS1vRsK6sBfDshmlN0Rk2kyxXVCtjQViLU1z
    utM8vC0zx7ydjbifIL4Ef5UY1q80IfcSNn6USVaiYNIddShcePO7xW1jx4bCSCF7
    YuRMVKJoodwj/nf0/G1FqSY1NZMn9Y4u9H5LrryCNCDNTaKdd7Ml0TUteunrwr84
    4ZionDOh3J5ZaaD0Acy1YqMyL9BFg9cMKJ/SOyRv/yzcToUlt7oiiwBM784QtSiZ
    w4wFSNWx2ewqwXYiqhYvvWkiPd+r3BtcFdfBl02zsSdIverUyaNYEJGqr91T8Eq5
    VfCpHFH7SUtlWOApe1C/J/O5iaOOChmRBnLS9YxafACMvKlabFHBEg8yJ9NXEhgK
    cB6QDn50dCsxrzderv4ihHhDbudp3lBIngW3riq1GJy14pDffZVvDihf8uVVhaQ8
    4Jn36hyACu17wMKsgZ79CGTo/dT/nNH1iAcWqXg7bqpfL3JBjwOg74l6V4vlrMl9
    s5j4+M40VUgmZqnODvOYbAyDceoZj2TI2l40/czx8xOXfS8NNss1pm1f/jUqN6TX
    rh30DYAd36QrnoKndI2AGe54hAC9fnUjm2RTshNzdRqBHeKcigdTrYnDZcT5ulSV
    WfXseBSkQ9GPSdNvQ2U1rCWBtiRIlkxD9fN52NO6HcUMqqPIOYN2F5PqLFsyedj/
    SPioDlYFyBq/F8QKKgwfMs9p5V7nOYhPwOVUa62Gx/QD3Luwj1J5yN2xmENFvLmi
    c9iNFb/+nWOvYh+OpK5oQA8nJHGpT2ljCmI9nDCvgkdnT7Pg5Yomrjp/P9UV7VLe
    0BW1JjtxEZ+McFy/4EfieoKQUPp9CtF2IO8Zc2ilkcPi6nIMNEf/AQFiS6Lqtdzp
    y9pApl3UHi+aicPzRLhNWpRA4RtMaRCbdD5cPIoWDpHLD6tnFxSygqKx+cJRu6eZ
    4wxlWvlIW0IqrSAyQrXDrz5cLEYurNODOzDjVXvohaVUlpTr9GkhgP//xVFuLSUl
    HYz1zHcaqxojSdf3cgDNyNUTw8PJDyNejYO+FH/82PFlgkduuPUSNc/USryrosJV
    IDNqAIyDemdD+IYJoOivuXlG2EorIFpfKKCW4MQSF9/3n+ABvTMlMak2dt2UvE8X
    /D1Elxqjgpy+aIKUW96G57C4/qZUEELfbkJpCZQh422gAv3S6n9YE+MipTiCaCph
    nnZ4KM+/RTyoKWL4WDNrJV7in7gn/WwHcf2qH0RqOEUFYZqjQCBsrv3xio28zzle
    tblI+GVo1/EVJQPiwWOq8AuimYu6rOVYo4/mUvHFbAUyL5Jd/nLkIniUq48+3cr8
    86dA2TgEIwMcY3NLEtQHNDQH4JutYBjygU58RRkQBuEU
    -----END ENCRYPTED PRIVATE KEY-----
    RSA_PRIV_KEY;


    /**
     * @var string The frontend application id
     */
    private string $issuer;

    /**
     * @var string The browser/os or the mobiele device description
     */
    private string $device;

    /**
     * @var string|null Cache generated token
     */
    protected ?string $generated_token;


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
        $time = time();
        $payload = $this->payload();
        $payload['iat'] = $time - 1;
        $payload['nbf'] = $time - 1;
        $payload['exp'] = $time + $this->lifetime();
        $payload['iss'] = $this->issuer();
        $payload['device'] = $this->device();

        return JWT::encode($payload, self::privateKey(), 'RS256');
    }


    /**
     * Get the private key for decode the token.
     *
     * @return OpenSSLAsymmetricKey
     * @throws Exception If private key failed.
     */
    private static function privateKey() : OpenSSLAsymmetricKey
    {
        $key = openssl_pkey_get_private(
            self::$private_key,
            'ectpresident'
        );

        if($key === false) {
            throw new Exception(openssl_error_string());
        }

        return $key;
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
        $publicKey = self::$public_key;
        $decoded = JWT::decode($token, $publicKey, ['RS256']);

        return static::decode($decoded);
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