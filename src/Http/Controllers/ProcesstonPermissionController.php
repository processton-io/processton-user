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

class ProcesstonPermissionController
{
    public function index(Request $request) : JsonResponse
    {
        $id = $request->get('id');

        $role = Role::findOrFail($id);

        if($request->method() == 'POST'){

            $requestData = $request->validate([
                'name' => 'required|string|max:255|unique:roles'
            ]);

            $role->__set('name' , $requestData['name']);

            $role->save();


            return response()->json([
                'next' => [
                    'type' => 'redirect',
                    'action' => route('processton-client.app.interaction', [
                        'app_slug' => 'setup',
                        'interaction_slug' => 'roles'
                    ])
                ],
                'message' => 'New Role is updated'
            ]);
        }

        $perms  = Permission::all()->groupBy('category');
        $permissionsGroup = [];
        foreach ($perms as $category => $permission) {
            $permissionsGroup[$category] = $permission->groupBy('sub_category');
        }

        $form = [];

        foreach ($permissionsGroup as $category => $permissionCategory) {
            $rows = [];
            foreach($permissionCategory as $subcat => $permissions){
                $elements = [];
                foreach($permissions as $permission){
                    $elements[] = ProcesstonForm::generateFormRowElement($permission->name, 'checkbox', $permission->key, false, false);
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
                            route('processton-app-user-roles.edit',[ 'id' => $id ]),
                            ProcesstonForm::generateFormSchema(
                                'Edit Role '.$role->name,
                                'edit',
                                ProcesstonForm::generateFormRows(...$rows)
                            ),
                            [],
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


    public function scanForPermissions(){


        $applications = config('processton-app-setup.apps');

        $permissions = [];

        foreach ($applications as $application) {

            foreach ($application['menu'] as $menu) {
                $permissions = array_merge($permissions, $menu['permission']);

            }
        }

        foreach ($permissions as $permission) {

            $mapping = $this->getMappings($permission);

            Permission::updateOrCreate([
                'key' => $permission
            ], [
                'category' => array_key_exists('category', $mapping) ? $mapping['category'] : 'Uncategorized',
                'sub_category' => array_key_exists('sub_category', $mapping) ? $mapping['sub_category'] : 'not defined',
                'name' => array_key_exists('name', $mapping) ? $mapping['name'] : $permission,
            ]);

        }
    }

    protected function getMappings($permission)
    {
        $mappings = config('module-user.permission_mappings');
        return array_key_exists($permission, $mappings) ? $mappings[$permission] : [];
    }
    
}
