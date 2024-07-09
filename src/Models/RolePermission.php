<?php

namespace Processton\ProcesstonUser\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = [
        'role_id',
        'permission_id',
        'is_granted'
    ];
}
