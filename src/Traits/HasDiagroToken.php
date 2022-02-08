<?php
namespace Diagro\Token\Traits;

use Diagro\Token\Model\Application;

/**
 * @package Diagro\Token\Traits
 */
trait HasDiagroToken
{


    /**
     * Determine if the user has the permission.
     *
     * @param iterable|string $abilities The abilities the users can do in permission (['read', ....] or 'read')
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
                    $able = $this->checkApplication($application, $abilities, $arguments);
                    $can = ($can === false ? (false || $able) : (true && $able));
                }
            }
        }

        return $can;
    }


    /**
     * Check if arguments for ability in the application.
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
            if ($application->hasPermission($permission)) {
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
     * @param $abilities
     * @return bool
     */
    public function canRead($abilities)
    {
        return $this->can($abilities, 'r');
    }


    /**
     * Check if has create scope on a permission(s).
     *
     * @param $abilities
     * @return bool
     */
    public function canCreate($abilities)
    {
        return $this->can($abilities, 'c');
    }


    /**
     * Check if has update scope on a permission(s).
     *
     * @param $abilities
     * @return bool
     */
    public function canUpdate($abilities)
    {
        return $this->can($abilities, 'u');
    }


    /**
     * Check if has delete scope on a permission(s).
     *
     * @param $abilities
     * @return bool
     */
    public function canDelete($abilities)
    {
        return $this->can($abilities, 'd');
    }


    /**
     * Check if has publish scope on a permission(s).
     *
     * @param $abilities
     * @return bool
     */
    public function canPublish($abilities)
    {
        return $this->can($abilities, 'p');
    }


    /**
     * Check if has export scope on a permission(s).
     *
     * @param $abilities
     * @return bool
     */
    public function canExport($abilities)
    {
        return $this->can($abilities, 'e');
    }


    /**
     * Check if has create,read,update or delete scope on a permission(s).
     *
     * @param $abilities
     * @return bool
     */
    public function canCRUD($abilities)
    {
        return $this->can($abilities, 'crud');
    }


    /**
     * Check if has update or create scope on a permission(s).
     *
     * @param $abilities
     * @return bool
     */
    public function canWrite($abilities)
    {
        return $this->can($abilities, 'cu');
    }


}