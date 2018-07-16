<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace KryuuCommon\ComponentRenderer;

use KryuuCommon\ComponentRenderer\Renderer\ComponentRendererFactory;
use KryuuCommon\ComponentRenderer\Strategy\ComponentStrategyFactory;
use KryuuCommon\ComponentRenderer\TokenParser\ForLoop;

return [
    __NAMESPACE__ => [
        'Tokens' => [
            ForLoop::class,
        ]
    ],
    // Add this section:
    'service_manager' => [
        'aliases' => [],
        'invokables' => [],
        'factories' => [
            'ComponentRenderer' => ComponentRendererFactory::class,
            'ComponentRendererStrategy' => ComponentStrategyFactory::class,
            'ComponentParser' => ComponentParserFactory::class,
            'Compiler' => CompilerFactory::class,
        ]
    ],
    'view_manager' => [
        'strategies' => array(
           'ComponentRendererStrategy'
        ), 
    ],
];