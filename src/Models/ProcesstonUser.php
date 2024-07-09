<?php

namespace Processton\ProcesstonUser\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcesstonUser extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active'
    ];
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }
}
