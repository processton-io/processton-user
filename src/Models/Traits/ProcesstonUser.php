<?php

namespace Processton\ProcesstonUser\Models\Traits;

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
        return $this->role->permissions();
    }
    public function hasPermission($permissionName){

        
        if($this->role->hasPermissionTo($permissionName)){

            return true;

        }else{
            //Might be permission is not mapped so far
            $permissionQuery = Permission::where(['key' => $permissionName]);

            if($permissionQuery->count() <= 0){

                $mapping = PermissionsMapping::getMapping($permissionName);

                Permission::create([
                    'key' => $permissionName,
                    'category' => array_key_exists('category', $mapping) ? $mapping['category'] : 'Uncategorized',
                    'sub_category' => array_key_exists('sub_category', $mapping) ? $mapping['sub_category'] : 'not defined',
                    'name' => array_key_exists('name', $mapping) ? $mapping['name'] : $permissionName,
                ]);

            }
        }

        if($this->role->id == 1){
            return true;
        }

        return false;

    }
}
