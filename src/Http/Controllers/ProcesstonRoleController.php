<?php

namespace Processton\ProcesstonUser\Http\Controllers;

use Processton\ProcesstonDataTable\ProcesstonDataTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Processton\ProcesstonForm\ProcesstonForm;
use Processton\ProcesstonInteraction\ProcesstonInteraction;
use Processton\ProcesstonInteraction\ProcesstonInteractionWidth;
use Processton\ProcesstonUser\Models\Role;
use Illuminate\Support\Str;

class ProcesstonRoleController
{
    public function index() : JsonResponse
    {
        
        $data = Role::paginate();

        return response()->json([
            'data' => ProcesstonDataTable::generateDataTableData([
                [
                    'value' => 'name',
                    'label' => 'Name'
                ]
            ], $data, false, true, true, [], [], [
                [
                    'type' => 'model',
                    'label' => 'Edit',
                    'action' => '/users/role/edit',
                    'color' => 'primary',
                    'attachments' => [
                        [
                            'key' => 'id',
                            'value' => 'id'
                        ]
                    ]
                ]
            ])
        ]);
    }

    public function addRole(Request $request) : JsonResponse
    {
        if($request->method() == 'POST'){

            $requestData = $request->validate([
                'name' => 'required|string|max:255|unique:Role'
            ]);

            $role = Role::create([
                'name' => $requestData['name']
            ]);

            $role->save();


            return response()->json([
                'next' => [
                    'type' => 'redirect',
                    'action' => route('processton-client.app.interaction', [
                        'app_slug' => 'setup',
                        'interaction_slug' => 'roles'
                    ])
                ],
                'message' => 'New Role is created'
            ]);
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
                            'Add new role',
                            route('processton-app-user.invite'),
                            ProcesstonForm::generateFormSchema(
                                'Add new role',
                                'create',
                                ProcesstonForm::generateFormRows(
                                    ProcesstonForm::generateFormRow([
                                        ProcesstonForm::generateFormRowElement('Name', 'text', 'name', 'Name', true)
                                    ])
                                )
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

    public function editRole(Request $request) : JsonResponse
    {
        $id = $request->get('id');

        $role = Role::findOrFail($id);

        if($request->method() == 'POST'){

            $requestData = $request->validate([
                'name' => 'required|string|max:255|unique:Role'
            ]);

            $role = $role->__set('name' , $requestData['name']);

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
                            route('processton-app-user.invite'),
                            ProcesstonForm::generateFormSchema(
                                'Edit Role '.$role->name,
                                'edit',
                                ProcesstonForm::generateFormRows(
                                    ProcesstonForm::generateFormRow([
                                        ProcesstonForm::generateFormRowElement('Name', 'text', 'name', 'Name', true)
                                    ])
                                )
                            ),
                            $role->toArray(),
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
    
}
