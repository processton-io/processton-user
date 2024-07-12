<?php

namespace Processton\ProcesstonUser\Static;

class PermissionsMapping{

    public static function getMapping($permission){

        $mappings = config('module-user.permission_mappings');
        return array_key_exists($permission, $mappings) ? $mappings[$permission] : [];

    }
}