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
            'doador' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/adm/doador[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => Controller\DoadorController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'recebedor' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/adm/recebedor[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => Controller\RecebedorController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'doacao' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/adm/doacao[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => Controller\DoacaoController::class,
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
