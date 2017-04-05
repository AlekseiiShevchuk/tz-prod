<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

trait Role
{

    private $roles = [
        'admin', 'client', 'partner'
    ];

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        if (!is_string($role) || !in_array($role, $this->roles)) {
            return false;
        }

        if (!str_contains($this->role, $role)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isStaff()
    {
        if ($this->isAdmin()) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * @return bool
     */
    public function isClient()
    {
        return $this->hasRole('client');
    }

    /**
     * @return bool
     */
    public function isPartner()
    {
        return $this->hasRole('partner');
    }
}