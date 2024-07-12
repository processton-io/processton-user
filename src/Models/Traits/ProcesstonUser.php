<?php

namespace Processton\ProcesstonUser\Models\Trait;

use Processton\ProcesstonUser\Models\Permission;
use Processton\ProcesstonUser\Models\Role;
use Processton\ProcesstonUser\Static\PermissionsMapping;

Trait ProcesstonUser
{
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }
    public function havePermission($permissionName){

        $userPermissionsQuery = $this->permissions()->where(['key', $permissionName]);

        if($userPermissionsQuery->count() > 0){

            return true;

        }else{
            //Might be permission is not mapped so far
            $permissionQuery = Permission::where(['key', $permissionName]);

            if($permissionQuery->count() <= 0){

                $mapping = PermissionsMapping::getMapping($permissionName);

                Permission::create([
                    'key' => $permissionName,
                    'category' => array_key_exists('category', $mapping) ? $mapping['category'] : 'Uncategorized',
                    'sub_category' => array_key_exists('sub_category', $mapping) ? $mapping['sub_category'] : 'not defined',
                    'name' => array_key_exists('name', $mapping) ? $mapping['name'] : $permission,
                ]);

            }
        }

        return false;

    }
}
