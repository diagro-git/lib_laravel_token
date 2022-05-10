<?php
namespace Diagro\Token\Model;

use ArrayAccess;
use Diagro\Token\Traits\HasDiagroToken;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use JsonSerializable;

class User implements Authenticatable, Authorizable, JsonSerializable
{

    use HasDiagroToken;


    /**
     * @var int The user ID
     */
    private int $id;

    /**
     * @var string The name of the user.
     */
    private string $name;

    /**
     * @var string locale code
     */
    private string $locale;

    /**
     * @var string language iso code
     */
    private string $lang;

    /**
     * @var string Timezone identifier
     */
    private string $timezone;

    /**
     * @var null|string The role of the user
     */
    private ?string $role = null;

    /**
     * @var null|Company The company the user is connected to.
     */
    private ?Company $company = null;

    /**
     * @var Application[] The applications the user/company is allowed.
     */
    private array $applications = [];


    /**
     * User constructor.
     *
     * @param int $id
     * @param string $name
     * @param string $role
     * @param Company|null $company
     */
    public function __construct(int $id, string $name, string $locale, string $lang, string $timezone, ?string $role = null, ?Company $company = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->role = $role;
        $this->company = $company;
        $this->locale = $locale;
        $this->lang = $lang;
        $this->timezone = $timezone;
    }


    /**
     * @return int
     */
    public function id() : int
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function name() : string
    {
        return $this->name;
    }


    /**
     * @return string
     */
    public function locale() : string
    {
        return $this->locale;
    }


    /**
     * @return string
     */
    public function lang() : string
    {
        return $this->lang;
    }


    /**
     * @return string
     */
    public function timezone() : string
    {
        return $this->timezone;
    }


    /**
     * @param string|null $role
     * @return string|null
     */
    public function role(?string $role = null) : ?string
    {
        if($role != null) {
            $this->role = $role;
        }

        return $this->role;
    }


    /**
     * @param Company|null $company
     * @return Company|null
     */
    public function company(?Company $company = null) : ?Company
    {
        if($company != null) {
            $this->company = $company;
        }

        return $this->company;
    }


    /**
     * @return Application[]
     */
    public function applications() : array
    {
        return $this->applications;
    }


    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }


    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier(): mixed
    {
        return $this->id;
    }


    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword(): string
    {
        return '';
    }


    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken(): string
    {
        return '';
    }


    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        //not supported in diagro
    }


    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName(): string
    {
        return '';
    }


    /**
     * Check if application exists for the user.
     *
     * @param string $name
     * @return bool
     */
    public function hasApplication(string $name) : bool
    {
        return isset($this->applications[$name]);
    }


    /**
     * Add application to user.
     *
     * @param Application $application
     * @return $this
     */
    public function addApplication(Application $application) : self
    {
        $this->applications[$application->name()] = $application;
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        $array = ['id' => $this->id(), 'name' => $this->name(), 'locale' => $this->locale(), 'lang' => $this->lang(), 'timezone' => $this->timezone()];
        if(!empty($role = $this->role())) $array['role'] = $role;

        return $array;
    }


}