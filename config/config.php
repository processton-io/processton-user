<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'base_path' => 'users',
    'menu_items' => [
        [
            "label" => "Users",
            "slug" => "users",
            "icon" => "user",
            "permission" => [
                'admin.setup.users'
            ],
            "hidden_links" => [
            ]
        ],
        [
            "label" => "Roles",
            "slug" => "roles",
            "icon" => "award",
            "permission" => [
                'admin.setup.roles'
            ],
            "hidden_links" => [
            ]
        ]
    ],
    'interactions' => [
        [
            "slug" => "users",
            'title' => 'Users',
            'subtitle' => '',
            'icon' => 'user',
            "schema" => [
                'breadcrumbs' => [
                    [
                        'label' => 'Dashboard',
                        'slug' => 'dashboard',
                        'icon' => 'pie-chart'
                    ],
                    [
                        'label' => 'Users',
                        'slug' => 'users',
                        'icon' => 'list'
                    ]
                ],
                'header_actions' => [
                    [
                        'type' => 'model',
                        'label' => 'Invite',
                        'icon' => 'plus',
                        'color' => 'primary',
                        'permission' => [],
                        'action' => '/users/invite'
                    ]
                ],
                'filters' => [

                ],
                'elements' => [
                    [
                        'type' => 'row',
                        'width' => [
                            'xxxs' => 12,
                            'xxs' => 12,
                            'xs' => 12,
                            'sm' => 12,
                            'md' => 12,
                            'lg' => 12,
                            'xl' => 12,
                            'xxl' => 12,
                            'xxxl' => 12,
                        ],
                        'elements' => [
                            [
                                'type' => 'data_table',
                                'title' => '',
                                'srcOfData' => [
                                    'api_endpoint' => '/users/list',
                                ],
                                'width' => [
                                    'xxxs' => 12,
                                    'xxs' => 12,
                                    'xs' => 12,
                                    'sm' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                    'xl' => 12,
                                    'xxl' => 12,
                                    'xxxl' => 12,
                                ],
                            ]
                        ]
                    ],
                ],
            ]
        ],
        [
            "slug" => "roles",
            'title' => 'Roles',
            'subtitle' => '',
            'icon' => 'award',
            "schema" => [
                'breadcrumbs' => [
                    [
                        'label' => 'Dashboard',
                        'slug' => 'dashboard',
                        'icon' => 'pie-chart'
                    ],
                    [
                        'label' => 'Roles',
                        'slug' => 'roles',
                        'icon' => 'award'
                    ]
                ],
                'header_actions' => [
                    [
                        'type' => 'model',
                        'label' => 'Add',
                        'icon' => 'plus',
                        'color' => 'primary',
                        'permission' => [],
                        'action' => '/users/roles/create'
                    ]
                ],
                'filters' => [

                ],
                'elements' => [
                    [
                        'type' => 'row',
                        'width' => [
                            'xxxs' => 12,
                            'xxs' => 12,
                            'xs' => 12,
                            'sm' => 12,
                            'md' => 12,
                            'lg' => 12,
                            'xl' => 12,
                            'xxl' => 12,
                            'xxxl' => 12,
                        ],
                        'elements' => [
                            [
                                'type' => 'data_table',
                                'title' => '',
                                'srcOfData' => [
                                    'api_endpoint' => '/users/roles/list',
                                ],
                                'width' => [
                                    'xxxs' => 12,
                                    'xxs' => 12,
                                    'xs' => 12,
                                    'sm' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                    'xl' => 12,
                                    'xxl' => 12,
                                    'xxxl' => 12,
                                ],
                            ]
                        ]
                    ],
                ],
            ]
        ]

    ],

    'charts' => [
        'stats' => [
            'total_users' => [
                'type' => 'stats_card',
                'title' => 'Total Users',
                'srcOfData' => [
                    'api_endpoint' => '/users/stats/count',
                ],
                'width' => [
                    'xxxs' => 12,
                    'xxs' => 12,
                    'xs' => 12,
                    'sm' => 12,
                    'md' => 12,
                    'lg' => 6,
                    'xl' => 6,
                    'xxl' => 6,
                    'xxxl' => 6,
                ],
            ],
            'new_users' => [
                'type' => 'stats_card',
                'title' => 'New Users',
                'srcOfData' => [
                    'api_endpoint' => '/users/stats/count_new',
                ],
                'width' => [
                    'xxxs' => 12,
                    'xxs' => 12,
                    'xs' => 12,
                    'sm' => 12,
                    'md' => 12,
                    'lg' => 6,
                    'xl' => 6,
                    'xxl' => 6,
                    'xxxl' => 6,
                ],
            ],
            'total_sessions' => [
                'type' => 'stats_card',
                'title' => 'Total Sessions',
                'srcOfData' => [
                    'api_endpoint' => '/users/stats/count_sessions',
                ],
                'width' => [
                    'xxxs' => 12,
                    'xxs' => 12,
                    'xs' => 12,
                    'sm' => 12,
                    'md' => 12,
                    'lg' => 6,
                    'xl' => 6,
                    'xxl' => 6,
                    'xxxl' => 6,
                ],
            ],
            'pending_validation' => [
                'type' => 'stats_card',
                'title' => 'Pending Validations',
                'srcOfData' => [
                    'api_endpoint' => '/users/stats/count_pending_validations',
                ],
                'width' => [
                    'xxxs' => 12,
                    'xxs' => 12,
                    'xs' => 12,
                    'sm' => 12,
                    'md' => 12,
                    'lg' => 6,
                    'xl' => 6,
                    'xxl' => 6,
                    'xxxl' => 6,
                ],
            ],
            'avg_sessions_duration' => [
                'type' => 'stats_card',
                'title' => 'Avg Duration',
                'srcOfData' => [
                    'api_endpoint' => '/users/stats/count_session_duration',
                ],
                'width' => [
                    'xxxs' => 12,
                    'xxs' => 12,
                    'xs' => 12,
                    'sm' => 12,
                    'md' => 12,
                    'lg' => 12,
                    'xl' => 12,
                    'xxl' => 12,
                    'xxxl' => 12,
                ],
            ]
        ]
    ],

    'resolvers' => [
        'user-invitation' => App\Resolvers\User\UserInvitationResolver::class,
        'user-block' => App\Resolvers\User\UserBlockResolver::class,
        'user-un-block' => App\Resolvers\User\UserUnBlockResolver::class,
    ]
];