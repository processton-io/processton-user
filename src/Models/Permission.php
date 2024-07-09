<?php

namespace Processton\ProcesstonUser\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'category',
        'sub_category',
        'name',
        'key',
    ];
}
