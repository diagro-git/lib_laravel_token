<?php
namespace Diagro\Token\Traits;

use Diagro\Token\Model\Application;

/**
 * @package Diagro\Token\Traits
 */
trait HasDiagroToken
{


    /**
     * Determine if the user has the ability or abilities for given permission(s).
     * You can use permissions from other applications also.
     * Just use <appName>.<rightName> instead of <rightName>.
     *
     * @param iterable|string $abilities The abilities the users can do in permission (['read', 'create', ...] or 'read')
     * @param array|mixed $arguments The permission or permissions.
     * @return bool
     */
    public function can($abilities, $arguments = []): bool
    {
        $can = false;

        if(is_array($arguments)) {
            foreach($arguments as $argument) {
                $able = $this->can($abilities, $argument);
                $can = ($can === false ? (false || $able) : (true && $able));
            }
        } else {
            if(str_contains($arguments, '.')) {
                list($application, $permission) = explode('.', $arguments, 2);
                if(isset($this->applications[$application])) {
                    $can = $this->checkApplication($this->applications[$application], $abilities, $permission);
                }
            } else {
                foreach ($this->applications as $application) {
                    if($application->hasPermission($arguments)) {
                        $able = $this->checkApplication($application, $abilities, $arguments);
                        $can = ($can === false ? (false || $able) : (true && $able));
                    }
                }
            }
        }

        return $can;
    }


    /**
     * Check if given ability or abilities is allowed for given permission from an application.
     *
     * @param Application $application
     * @param $abilities
     * @param string $permission
     * @return bool
     */
    private function checkApplication(Application $application, $abilities, string $permission) : bool
    {
        $can = false;
        if(is_string($abilities)) {
            $abilities = [$abilities];
        }

        foreach($abilities as $ability) {
            $firstChar = strtolower(substr($ability, 0, 1));
            $p = $application->permissions()[$permission];
            switch ($firstChar) {
                case 'r':
                    $can = $can === false ? (false || $p->read) : (true && $p->read);
                    break;
                case 'c':
                    $can = $can === false ? (false || $p->create) : (true && $p->create);
                    break;
                case 'u':
                    $can = $can === false ? (false || $p->update) : (true && $p->update);
                    break;
                case 'd':
                    $can = $can === false ? (false || $p->delete) : (true && $p->delete);
                    break;
                case 'p':
                    $can = $can === false ? (false || $p->publish) : (true && $p->publish);
                    break;
                case 'e':
                    $can = $can === false ? (false || $p->export) : (true && $p->export);
                    break;
            }
        }

        return $can;
    }


    /**
     * Check if the user has a given role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role) : bool
    {
        return $this->role() == $role;
    }


    /**
     * Check if has read scope on a permission(s).
     *
     * @param $permissions
     * @return bool
     */
    public function canRead($permissions)
    {
        return $this->can('read',$permissions);
    }


    /**
     * Check if has create scope on a permission(s).
     *
     * @param $permissions
     * @return bool
     */
    public function canCreate($permissions)
    {
        return $this->can('create', $permissions);
    }


    /**
     * Check if has update scope on a permission(s).
     *
     * @param $permissions
     * @return bool
     */
    public function canUpdate($permissions)
    {
        return $this->can('update', $permissions);
    }


    /**
     * Check if has delete scope on a permission(s).
     *
     * @param $permissions
     * @return bool
     */
    public function canDelete($permissions)
    {
        return $this->can('delete', $permissions);
    }


    /**
     * Check if has publish scope on a permission(s).
     *
     * @param $permissions
     * @return bool
     */
    public function canPublish($permissions)
    {
        return $this->can('publish', $permissions);
    }


    /**
     * Check if has export scope on a permission(s).
     *
     * @param $permissions
     * @return bool
     */
    public function canExport($permissions)
    {
        return $this->can('export', $permissions);
    }


    /**
     * Check if has create,read,update or delete scope on a permission(s).
     *
     * @param $permissions
     * @return bool
     */
    public function canCRUD($permissions)
    {
        return $this->can(['c','r','u','d'],$permissions);
    }


    /**
     * Check if has update or create scope on a permission(s).
     *
     * @param $permissions
     * @return bool
     */
    public function canWrite($permissions)
    {
        return $this->can(['c', 'u'],$permissions);
    }


}