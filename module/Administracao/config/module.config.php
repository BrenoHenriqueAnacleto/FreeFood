<?php

namespace Administracao;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'adm' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/adm[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'usuario' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/usuario[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => Controller\UsuarioController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],
];
