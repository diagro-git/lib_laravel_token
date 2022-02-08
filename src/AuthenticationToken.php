<?php
namespace Diagro\Token;

use Diagro\Token\Model\User;
use Exception;

/**
 * This class contains the decoded information of an AT token.
 *
 * @package App
 */
class AuthenticationToken extends Token
{


    /**
     * @var User
     */
    private User $user;


    /**
     * Make an AT token.
     *
     * @param User $user
     * @param string $issuer
     * @param string $device
     */
    public function __construct(User $user, string $issuer, string $device)
    {
        parent::__construct($issuer, $device);
        $this->user = $user;
    }


    /**
     * @return User
     */
    public function user() : User
    {
        return $this->user;
    }


    /**
     * Get the AT token in JWT format.
     *
     * @return string
     * @throws Exception The private key failed
     */
    public function token() : string
    {
        if(empty($this->generated_token)) {
            $this->generated_token = $this->createToken();
        }

        return $this->generated_token;
    }


    /**
     * Returns the AT token in JWT format.
     *
     * @return string
     * @throws Exception The private key failed
     */
    public function __toString(): string
    {
        return $this->token();
    }


    /**
     * The payload for the token.
     *
     * @return array
     */
    protected function payload(): array
    {
        return ['user' => $this->user];
    }

    /**
     * Make the token class object from the decoded data.
     *
     * @param object $decoded
     * @return AuthenticationToken
     */
    protected static function decode(object $decoded): static
    {
        $user = new User($decoded->user->id, $decoded->user->name, $decoded->user->locale, $decoded->user->lang, $decoded->user->timezone);
        return new self($user, $decoded->iss, $decoded->device);
    }


    /**
     * Lifespan is one month.
     *
     * @return int
     */
    protected function lifetime(): int
    {
        return 60*60*24*30;
    }


}
