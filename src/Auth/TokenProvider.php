<?php
namespace Diagro\Token\Auth;

use BadMethodCallException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class TokenProvider implements UserProvider
{


    private string $token_class_name;


    public function __construct(string $token_class_name)
    {
        $this->token_class_name = $token_class_name;
    }


    /**
     * Retrieve a user by their unique identifier.
     *
     * @param mixed $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function retrieveById($identifier)
    {
        $user = app()->make($this->token_class_name)?->user();
        if($user?->id() == $identifier) {
            return $user;
        }

        return null;
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param mixed $identifier
     * @param string $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        throw new BadMethodCallException("Not supported");
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        throw new BadMethodCallException("Not supported");
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        throw new BadMethodCallException("Not supported");
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        throw new BadMethodCallException("Not supported");
    }


}