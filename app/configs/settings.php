<?php

return [

    'settings' => [

        'app_development_mode' => APPDEVELOPMENT,

        // set to false in production

        'displayErrorDetails' => true, 

        // ViewEngine settings

        'view' => [

            'template_path' => __DIR__ . '/../templates',

            'twig' => [

                'cache' => __DIR__ . '/../../cache/twig',

                'debug' => true,

                'auto_reload' => true

            ],

        ],

        // Logger Monolog settings

        'logger' => [

            'name' => 'LppmCrmWeb',

            'path' => __DIR__ . '/../../logs/log_'.  date('Y-m-d').'.log',

        ],

        'database' => [

            'sibit' => [

                'host'      => 'localhost',

                'database'  => 'veresis',

                'username'  => 'root',

                'password'  => '',

                'table_prefix' => 'crm_'

            ],

            'kigtest' => [

                'host'      => 'localhost',

                'database'  => 'veresis',

                'username'  => 'root',

                'password'  => '',

                'table_prefix' => 'crm_'

            ],

            'kigproduction' => [ 

                'host'      => 'localhost',

                'database'  => 'veresis',

                'username'  => 'root',

                'password'  => '',

                'table_prefix' => 'crm_'

            ]

        ],

        'externalApiUrl' => [

            'sibit' => [

                'lppmApiUrl' => [

                    'baseUrl' => 'http://veresis.test/lppmapi/api/',

                    'loginUrl' => 'Account/Login/',

                    'cookieName' => '.ASPXAUTH',

                    'loginData' => [

                        'username' => 'VeresisCrm',

                        'password' => 'Veresis.Crm:1620'

                    ]

                ]

            ],

            'kigtest' => [

                'lppmApiUrl' => [

                    'baseUrl' => 'https://testbe.kig.si/lppmapi/api/',

                    'loginUrl' => 'Account/Login/',

                    'cookieName' => '.ASPXAUTH',

                    'loginData' => [

                        'username' => 'VeresisCrm',

                        'password' => 'Veresis.Crm:1620'

                    ]

                ]

            ],

            'kigproduction' => [

                'lppmApiUrl' => [

                    'baseUrl' => 'http://veresis.test/lppmapi/api/',

                    'loginUrl' => 'Account/Login/',

                    'cookieName' => '.ASPXAUTH',

                    'loginData' => [

                        'username' => 'VeresisCrm',

                        'password' => 'Veresis.Crm:1620'

                    ]

                ]

            ]

        ],

        'mailMeblo' => '', //splet@sibit.si

        'mailSender' => [

            'sibit' => [

                'mailFrom'      => 'veresis@kig.si',

                'mailServerAuthUser'  => 'veresis@kig.si',

                'mailServerAuthPassword'  => 'Kig.V3r3s1s.123',

                'mailServerHost' => 'smtp.office365.com',

                'mailServerPort'  => '587'

            ],

            'kigtest' => [

                'mailFrom'      => 'veresis@kig.si',

                'mailServerAuthUser'  => 'veresis@kig.si',

                'mailServerAuthPassword'  => 'Kig.V3r3s1s.123',

                'mailServerHost' => 'smtp.office365.com',

                'mailServerPort'  => '587'

            ],

            'kigproduction' => [

                'mailFrom'      => 'veresis@kig.si',

                'mailServerAuthUser'  => 'veresis@kig.si',

                'mailServerAuthPassword'  => 'Kig.V3r3s1s.123',

                'mailServerHost' => 'smtp.office365.com',

                'mailServerPort'  => '587'

            ]

        ]

    ],    

];