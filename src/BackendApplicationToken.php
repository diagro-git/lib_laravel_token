<?php
namespace Diagro\Token;

use Diagro\Token\Model\User;
use Exception;

/**
 * This class contains the decoded information of an BAT token.
 *
 * @package App
 */
class BackendApplicationToken extends Token
{


    /**
     * The backend application ID
     *
     * @var int
     */
    private int $id;


    /**
     * Make an BAT token.
     *
     * @param int $id The backend application ID
     * @param string $issuer The name of the backend application
     * @param string $device The name of the device which is set to "Diagro platform"
     */
    public function __construct(int $id, string $issuer, string $device = 'Diagro platform')
    {
        parent::__construct($issuer, $device);
        $this->id = $id;
    }


    /**
     * @return int
     */
    public function id() : int
    {
        return $this->id;
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
        return ['id' => $this->id()];
    }

    /**
     * Make the token class object from the decoded data.
     *
     * @param object $decoded
     * @return BackendApplicationToken
     */
    protected static function decode(object $decoded): static
    {
        return new self($decoded->id, $decoded->iss, $decoded->device);
    }


    /**
     * Lifespan is forever (100 years is forever)
     *
     * @return int
     */
    protected function lifetime(): int
    {
        return 60*60*24*365*100;
    }


}
