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
use Illuminate\Support\Facades\Hash;

class ProcesstonUserController
{
    public function index(Request $request) : JsonResponse
    {
        !$request->user()->hasPermission('admin.setup.users') && abort(403, 'Unauthorized');
        $model = config('auth.providers.users.model');
        $data = $model::with('role')->paginate();

        return response()->json([
            'data' => ProcesstonDataTable::generateDataTableData([
                [
                    'value' => 'name',
                    'label' => 'Profile',
                    'type' => 'info_with_ui_avatars',
                    'config' => [
                        'mapping' => [
                            'name' => 'name',
                            'email' => 'email',
                            'description' => 'role'
                        ]
                    ]
                ],
                [
                    'value' => 'role.name',
                    'label' => 'Role',
                ],
                [
                    'value' => 'is_active',
                    'label' => 'Status',
                    'type' => 'status',
                    'config' => [
                        'mapping' => [
                            
                            [
                                'value' => 0,
                                'icon' => 'user',
                                'message' => 'Inactive',
                                'color' => 'red'
                            ],
                            [
                                'value' => 1,
                                'icon' => 'user',
                                'message' => 'Active',
                                'color' => 'green'
                            ]
                        ]
                    ]
                ]
            ], $data, false, true, true, [], [], [
                [
                    'type' => 'model',
                    'label' => 'Change Role',
                    'action' => '/users/change-role',
                    'attachments' => [
                        [
                            'key' => 'id',
                            'value' => 'id'
                        ]
                    ]
                ],
                [
                    'type' => 'model',
                    'label' => 'Change Password',
                    'action' => '/users/change-password',
                    'attachments' => [
                        [
                            'key' => 'id',
                            'value' => 'id'
                        ]
                    ]
                ],
                [
                    'type' => 'model',
                    'label' => 'Block',
                    'action' => '/users/block',
                    'color' => 'dangerous',
                    'attachments' => [
                        [
                            'key' => 'id',
                            'value' => 'id'
                        ]
                    ],
                    'conditions' => [
                        [
                            'operator' => 'EQUALS',
                            'key' => 'is_active',
                            'value' => 1
                        ]
                    ]
                ],
                [
                    'type' => 'model',
                    'label' => 'Un Block',
                    'action' => '/users/un-block',
                    'color' => 'success',
                    'attachments' => [
                        [
                            'key' => 'id',
                            'value' => 'id'
                        ]
                    ],
                    'conditions' => [
                        [
                            'operator' => 'EQUALS',
                            'key' => 'is_active',
                            'value' => 0
                        ]
                    ]
                ]
            ])
        ]);
    }

    public function invite(Request $request) : JsonResponse
    {
        !$request->user()->hasPermission('admin.setup.users.invite') && abort(403, 'Unauthorized');
        if($request->method() == 'POST'){

            $model = config('auth.providers.users.model');

            $requestData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:' . $model,
                'role.value' => 'required|exists:roles,id'
            ]);

            $user = $model::create([
                'name' => $requestData['name'],
                'email' => $requestData['email']
            ]);

            $user->__set('role_id', $requestData['role']['value']);
            $user->__set('invitation_token', Str::random(60));
            $user->save();

            $resolver = config('module-user.resolvers.user-invitation', false);

            if($resolver !== false){
                $resolver::handle($user);
            }


            return response()->json([
                'next' => [
                    'type' => 'redirect',
                    'action' => route('processton-client.app.interaction', [
                        'app_slug' => 'setup',
                        'interaction_slug' => 'users'
                    ])
                ],
                'message' => 'User has been invited'
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
                            'Invite new user',
                            route('processton-app-user.invite'),
                            ProcesstonForm::generateFormSchema(
                                'Invite new user',
                                'create',
                                ProcesstonForm::generateFormRows(
                                    ProcesstonForm::generateFormRow([
                                        ProcesstonForm::generateFormRowElement('Name', 'text', 'name', 'Name', true),
                                        ProcesstonForm::generateFormRowElement('Email address', 'text', 'email', 'Email', true),
                                        ProcesstonForm::generateFormRowElement(
                                            'Role', 
                                            'simple_select', 
                                            'role', 
                                            '', 
                                            true, 
                                            'Please select role of new user',
                                            [],
                                            Role::all()->map(function($role){
                                                return [
                                                    'value' => $role->id,
                                                    'label' => $role->name
                                                ];
                                            })
                                        )
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

    public function allowUser(Request $request): JsonResponse
    {
        !$request->user()->hasPermission('admin.setup.users.unblock') && abort(403, 'Unauthorized');

        $id = $request->get('id', false);
        
        $model = config('auth.providers.users.model');

        $user = $model::find($id);

        if ($request->method() == 'POST') {


            $user->__set('is_active', 1);
            $user->__set('note', '');
            $user->save();


            $resolver = config('module-user.resolvers.user-un-block', false);

            if ($resolver !== false) {
                $resolver::handle($user);
            }

            return response()->json([
                'next' => [
                    'type' => 'redirect',
                    'action' => route('processton-client.app.interaction', [
                        'app_slug' => 'setup',
                        'interaction_slug' => 'users'
                    ])
                ],
                'message' => 'User is allowed'
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
                            'Activate user '.$user->name,
                            route('processton-app-user.un_block',[
                                'id' => $user->id
                            ]),
                            ProcesstonForm::generateFormSchema(
                                'Activate user ' . $user->name,
                                'Activate',
                                ProcesstonForm::generateFormRows(
                                    ProcesstonForm::generateFormRow([
                                        ProcesstonForm::generateFormRowElement('Note', 'text_area', 'note', 'Note', false)
                                    ])
                                )
                            ),
                            [],
                            [],
                            '',
                            ProcesstonInteraction::width(12, 12, 12)
                        )
                    ], ProcesstonInteraction::width(12, 12, 12)
                    )
                ]
            )
        ]);
    }

    public function blockUser(Request $request): JsonResponse
    {
        !$request->user()->hasPermission('admin.setup.users.block') && abort(403, 'Unauthorized');

        $id = $request->get('id', false);

        $model = config('auth.providers.users.model');

        $user = $model::find($id);

        if ($request->method() == 'POST') {

            $requestData = $request->validate([
                'note' => 'required|string|max:255'
            ]);

            $user->__set('is_active', 0);
            $user->__set('note', $requestData['note'] ?? '');
            $user->save();


            $resolver = config('module-user.resolvers.user-block', false);

            if ($resolver !== false) {
                $resolver::handle($user);
            }

            return response()->json([
                'next' => [
                    'type' => 'redirect',
                    'action' => route('processton-client.app.interaction', [
                        'app_slug' => 'setup',
                        'interaction_slug' => 'users'
                    ])
                ],
                'message' => 'User is blocked'
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
                            'Block user ' . $user->name,
                            route('processton-app-user.block', [
                                'id' => $user->id
                            ]),
                            ProcesstonForm::generateFormSchema(
                                'Block user ' . $user->name,
                                'Block',
                                ProcesstonForm::generateFormRows(
                                    ProcesstonForm::generateFormRow([
                                        ProcesstonForm::generateFormRowElement('Note', 'text_area', 'note', 'Note', true)
                                    ])
                                )
                            ),
                            [],
                            [],
                            '',
                            ProcesstonInteraction::width(12, 12, 12)
                        )
                    ],
                        ProcesstonInteraction::width(12, 12, 12)),
                ]
            )
        ]);
    }

    public function changeRole(Request $request): JsonResponse
    {
        !$request->user()->hasPermission('admin.setup.users.changerole') && abort(403, 'Unauthorized');

        $id = $request->get('id', false);

        $model = config('auth.providers.users.model');

        $user = $model::with('role')->findOrFail($id);

        if ($request->method() == 'POST') {


            $requestData = $request->validate([
                'role_id' => 'required'
            ]);

            $user->__set('role_id', $requestData['role_id']['value']);
            $user->save();


            return response()->json([
                'next' => [
                    'type' => 'redirect',
                    'action' => route('processton-client.app.interaction', [
                        'app_slug' => 'setup',
                        'interaction_slug' => 'users'
                    ])
                ],
                'message' => 'User role been updated'
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
                    ProcesstonInteraction::generateRow(
                        [
                            ProcesstonForm::generateForm(
                                'Edit Role for '.$user->name,
                                route('processton-app-user.change_role', [
                                    'id' => $user->id
                                ]),
                                ProcesstonForm::generateFormSchema(
                                    'Edit Role for '.$user->name,
                                    'edit',
                                    ProcesstonForm::generateFormRows(
                                        ProcesstonForm::generateFormRow([
                                            ProcesstonForm::generateFormRowElement(
                                                'Role',
                                                'simple_select',
                                                'role_id',
                                                '',
                                                true,
                                                'Please select role of new user',
                                                [],
                                                Role::all()->map(function ($role) {
                                                    return [
                                                        'value' => $role->id,
                                                        'label' => $role->name
                                                    ];
                                                })
                                            )
                                        ])
                                    )
                                ),
                                $user->toArray(),
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

    public function changePassword(Request $request): JsonResponse
    {
        !$request->user()->hasPermission('admin.setup.users.changepassword') && abort(403, 'Unauthorized');

        $id = $request->get('id', false);

        $model = config('auth.providers.users.model');

        $user = $model::findOrFail($id);
        
        if ($request->method() == 'POST') {
            
            $requestData = $request->validate([
                'method' => 'required'
            ]);

            if($requestData['method']['value'] == 'manual'){
                $requestData = $request->validate([
                    'password' => 'required|confirmed'
                ]);
                $user->__set('password', Hash::make($requestData['password']));
                $user->save();
            }

            $resolver = config('module-user.resolvers.user-change-password', false);

            if ($resolver !== false) {
                $resolver::handle($user, $requestData['method']['value']);
            }


            return response()->json([
                'next' => [
                    'type' => 'redirect',
                    'action' => route('processton-client.app.interaction', [
                        'app_slug' => 'setup',
                        'interaction_slug' => 'users'
                    ])
                ],
                'message' => 'Request processed'
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
                    ProcesstonInteraction::generateRow(
                        [
                            ProcesstonForm::generateForm(
                                'Edit Password for ' . $user->name,
                                route('processton-app-user.change_password', [
                                    'id' => $user->id
                                ]),
                                ProcesstonForm::generateFormSchema(
                                    'Edit Password for ' . $user->name,
                                    'submit',
                                    ProcesstonForm::generateFormRows(
                                        ProcesstonForm::generateFormRow([
                                            ProcesstonForm::generateFormRowElement(
                                                'Method',
                                                'simple_select',
                                                'method',
                                                '',
                                                true,
                                                'How do you want to proceed',
                                                [],
                                                [
                                                    [
                                                        'value' => 'email',
                                                        'label' => 'Send email'
                                                    ],
                                                    [
                                                        'value' => 'manual',
                                                        'label' => 'Manual'
                                                    
                                                    ]
                                                ]
                                            ),
                                            ProcesstonForm::generateFormRowElement(
                                                'Password',
                                                'password',
                                                'password',
                                                '',
                                                true,
                                                '',
                                                [
                                                    [
                                                        'key' => 'method.value',
                                                        'operator' => 'EQUALS',
                                                        'value' => 'manual'
                                                    ]
                                                ],
                                                []
                                            ),
                                            ProcesstonForm::generateFormRowElement(
                                                'Confirm password',
                                                'password',
                                                'password_confirmation',
                                                '',
                                                true,
                                                '',
                                                [
                                                    [
                                                        'key' => 'method.value',
                                                        'operator' => 'EQUALS',
                                                        'value' => 'manual'
                                                    ]
                                                ],
                                                []
                                            )
                                        ])
                                    )
                                ),
                                [
                                    'method' => 'manual'
                                ],
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
