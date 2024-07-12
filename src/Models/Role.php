<?php

namespace Processton\ProcesstonUser\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
    ];
    public function users()
    {
        return $this->hasMany(config('auth.providers.users.model'), 'role_id');
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    public function givePermissionTo($permission)
    {
        if(!$this->hasPermissionTo($permission->key)){
            $this->permissions()->attach($permission);
        }
    }

    public function revokePermissionTo($permission)
    {
        if($this->hasPermissionTo($permission->key)){
            $this->permissions()->detach($permission->id);
        }
    }

    public function hasPermissionTo($permission)
    {
        return $this->permissions->contains('key', $permission);
    }
}
