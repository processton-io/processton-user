<?php

namespace Processton\ProcesstonUser\Http\Controllers;

use Processton\ProcesstonDataTable\ProcesstonDataTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Processton\ProcesstonForm\ProcesstonForm;
use Processton\ProcesstonInteraction\ProcesstonInteraction;
use Processton\ProcesstonInteraction\ProcesstonInteractionWidth;
use Processton\ProcesstonUser\Models\Permission;
use Processton\ProcesstonUser\Models\Role;
use Illuminate\Support\Str;
use Processton\ProcesstonUser\Static\PermissionsMapping;

class ProcesstonPermissionController
{
    public function index(Request $request) : JsonResponse
    {
        !$request->user()->hasPermission('admin.setup.roles.permissions') && abort(403, 'Unauthorized');

        $id = $request->get('id');

        $role = Role::findOrFail($id);

        if($request->method() == 'POST'){

            $perms = Permission::all();
            foreach($perms as $perm){
                if($request->get($perm->key , false) == true){
                    $role->givePermissionTo($perm);
                }else{
                    $role->revokePermissionTo($perm);
                }
            }


            return response()->json([
                'next' => [
                    'type' => 'redirect',
                    'action' => route('processton-client.app.interaction', [
                        'app_slug' => 'setup',
                        'interaction_slug' => 'roles'
                    ])
                ],
                'message' => 'Permissions updated'
            ]);
        }

        $perms  = Permission::all()->groupBy('category');
        $permissionsGroup = [];
        foreach ($perms as $category => $permission) {
            $permissionsGroup[$category] = $permission->groupBy('sub_category');
        }

        $rows = [];

        $values = [];

        foreach ($permissionsGroup as $category => $permissionCategory) {
            
            foreach($permissionCategory as $subcat => $permissions){
                $elements = [];
                foreach($permissions as $permission){
                    $elements[] = ProcesstonForm::generateFormRowElement($permission->name, 'checkbox', $permission->key, false, false);
                    if($role->hasPermissionTo($permission->key)){
                        $values[$permission->key] = true;
                    }
                }
                $rows[] = ProcesstonForm::generateFormRow(
                    $elements,
                    [],
                    $category != 'Uncategorized' ? $category : '',
                    $subcat != 'not defined' ? $subcat : '' 
                );
            }

        }
        
        return response()->json([
            'interaction' => ProcesstonInteraction::generateInteraction(
                'Dashboard',
                'dashboard',
                'Dashboard',
                'dashboard',
                [],
                [],
                [
                    ProcesstonInteraction::generateRow([
                        ProcesstonForm::generateForm(
                            'Edit Role '.$role->name,
                            route('processton-app-user-roles.permissions',[ 'id' => $id ]),
                            ProcesstonForm::generateFormSchema(
                                'Edit Role '.$role->name,
                                'edit',
                                ProcesstonForm::generateFormRows(...$rows)
                            ),
                            $values,
                            [],
                            '',
                            ProcesstonInteraction::width(12, 12, 12)
                        )
                    ],
                    ProcesstonInteraction::width(12, 12, 12)
                ),
                ]
            )
        ]);
    }


    public function scanForPermissions(Request $request){

        !$request->user()->hasPermission('admin.setup.roles.permissions') && abort(403, 'Unauthorized');

        $applications = config('processton-app-setup.apps');

        $permissions = [];

        foreach ($applications as $application) {

            foreach ($application['menu'] as $menu) {
                $permissions = array_merge($permissions, $menu['permission']);

            }
        }

        foreach ($permissions as $permission) {

            $mapping = PermissionsMapping::getMapping($permission);

            Permission::updateOrCreate([
                'key' => $permission
            ], [
                'category' => array_key_exists('category', $mapping) ? $mapping['category'] : 'Uncategorized',
                'sub_category' => array_key_exists('sub_category', $mapping) ? $mapping['sub_category'] : 'not defined',
                'name' => array_key_exists('name', $mapping) ? $mapping['name'] : $permission,
            ]);

        }
    }
    
}
