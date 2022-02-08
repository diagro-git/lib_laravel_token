<?php
namespace Diagro\Token;

use Diagro\Token\Model\Application;
use Diagro\Token\Model\Company;
use Diagro\Token\Model\Permission;
use Diagro\Token\Model\User;
use Exception;

/**
 * This class contains the decoded information of an AAT token.
 *
 * @package App
 */
class ApplicationAuthenticationToken extends Token
{


    /**
     * @var User The user for this token.
     */
    private User $user;

    /**
     * @var Company The company for this token.
     */
    private Company $company;

    /**
     * @var array The backend applications where user/company has permissions to.
     */
    private array $applications = [];


    /**
     * Make an AAT token.
     *
     * @param User $user
     * @param Company $company
     * @param Application[] $applications
     * @param string $issuer
     * @param string $device
     */
    public function __construct(User $user, Company $company, array $applications, string $issuer, string $device)
    {
        parent::__construct($issuer, $device);
        $this->user = $user;
        $this->company = $company;
        $this->applications = $applications;

        //link them
        $this->user->company($company);

        foreach($applications as $application) {
            $this->user->addApplication($application);
        }
    }


    /**
     * @return User
     */
    public function user() : User
    {
        return $this->user;
    }


    /**
     * @return Company|null
     */
    public function company() : ?Company
    {
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
     * Get the AAT token in JWT format.
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
        return ['user' => $this->user(), 'company' => $this->company(), 'applications' => $this->applications()];
    }

    /**
     * Make the token class object from the decoded data.
     *
     * @param object $decoded
     * @return ApplicationAuthenticationToken
     */
    protected static function decode(object $decoded): static
    {
        $role = (property_exists($decoded->user, 'role') ? $decoded->user->role : null);
        $user = new User($decoded->user->id, $decoded->user->name, $decoded->user->locale, $decoded->user->lang, $decoded->user->timezone, $role);
        $company = new Company($decoded->company->id, $decoded->company->name, $decoded->company->country, $decoded->company->currency);

        $applications = [];
        foreach($decoded->applications as $index => $application) {
            $applications[$index] = new Application($application->id, $application->name);
            foreach($application->permissions as $name => $permission) {
                $applications[$index]->addPermission($name, new Permission($permission));
            }
        }

        return new self($user, $company, $applications, $decoded->iss, $decoded->device);
    }

    /**
     * Lifespan is 4 hours.
     *
     * @return int
     */
    protected function lifetime(): int
    {
        return 60*60*4;
    }


}
