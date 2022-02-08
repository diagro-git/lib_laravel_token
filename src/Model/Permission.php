<?php
namespace Diagro\Token\Model;

use JsonSerializable;

class Permission implements JsonSerializable
{

    /**
     * @var bool Has read access
     */
    public bool $read = false;

    /**
     * @var bool Has create access
     */
    public bool $create = false;

    /**
     * @var bool Has update access
     */
    public bool $update = false;

    /**
     * @var bool Has delete access
     */
    public bool $delete = false;

    /**
     * @var bool Has publish access
     */
    public bool $publish = false;

    /**
     * @var bool Has export access
     */
    public bool $export = false;


    /**
     * Permission constructor.
     * @param string|null $permissions
     */
    public function __construct(?string $permissions = null)
    {
        if($permissions != null) {
            foreach (str_split($permissions) as $item) {
                switch ($item) {
                    case 'r':
                        $this->read = true;
                        break;
                    case 'c':
                        $this->create = true;
                        break;
                    case 'd':
                        $this->delete = true;
                        break;
                    case 'u':
                        $this->update = true;
                        break;
                    case 'p':
                        $this->publish = true;
                        break;
                    case 'e':
                        $this->export = true;
                        break;
                }
            }
        }
    }


    /**
     * Set if can read or not.
     *
     * @param bool $can
     * @return $this
     */
    public function canRead(bool $can = true) : self
    {
        $this->read = $can;
        return $this;
    }


    /**
     * Set if can create or not.
     *
     * @param bool $can
     * @return $this
     */
    public function canCreate(bool $can = true) : self
    {
        $this->create = $can;
        return $this;
    }


    /**
     * Set if can update or not.
     *
     * @param bool $can
     * @return $this
     */
    public function canUpdate(bool $can = true) : self
    {
        $this->update = $can;
        return $this;
    }


    /**
     * Set if can delete or not.
     *
     * @param bool $can
     * @return $this
     */
    public function canDelete(bool $can = true) : self
    {
        $this->delete = $can;
        return $this;
    }


    /**
     * Set if can publish or not.
     *
     * @param bool $can
     * @return $this
     */
    public function canPublish(bool $can = true) : self
    {
        $this->publish = $can;
        return $this;
    }


    /**
     * Set if can export or not.
     *
     * @param bool $can
     * @return $this
     */
    public function canExport(bool $can = true) : self
    {
        $this->export = $can;
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $str = '';
        if($this->read === true) $str .= 'r';
        if($this->create === true) $str .= 'c';
        if($this->update === true) $str .= 'u';
        if($this->delete === true) $str .= 'd';
        if($this->publish === true) $str .= 'p';
        if($this->export === true) $str .= 'e';

        return $str ?? null;
    }


}