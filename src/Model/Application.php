<?php
namespace Diagro\Token\Model;

use ArrayAccess;
use JsonSerializable;

class Application implements JsonSerializable
{

    /**
     * @var int The application ID.
     */
    private int $id;

    /**
     * @var string The name of the application
     */
    private string $name;

    /**
     * @var Permission[] The permissions this application has and which the user/company is allowed to.
     */
    private array $permissions = [];


    /**
     * Application constructor.
     *
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
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
     * @return Permission[]
     */
    public function permissions() : array
    {
        return $this->permissions;
    }


    /**
     * Add permission
     *
     * @param string $permissionName
     * @param Permission $permission
     * @return $this
     */
    public function addPermission(string $permissionName, Permission $permission) : self
    {
        $this->permissions[$permissionName] = $permission;
        return $this;
    }


    /**
     * Checks if application has permission
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission) : bool
    {
        return isset($this->permissions[$permission]);
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return ['id' => $this->id(), 'name' => $this->name(), 'permissions' => $this->permissions()];
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name();
    }


}