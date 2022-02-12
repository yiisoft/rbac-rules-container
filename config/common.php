<?php

declare(strict_types=1);

use Yiisoft\Rbac\Rules\Container\RulesContainer;
use Yiisoft\Rbac\RulesFactoryInterface;

/* @var array $params */

return [
    RulesFactoryInterface::class => [
        'class' => RulesContainer::class,
        '__construct()' => [
            'definitions' => $params['yiisoft/rbac-rules-container']['rules'],
            'validate' => $params['yiisoft/rbac-rules-container']['validate'],
        ],
    ],
];
