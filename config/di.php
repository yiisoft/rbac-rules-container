<?php

declare(strict_types=1);

use Yiisoft\Rbac\RuleFactoryInterface;
use Yiisoft\Rbac\Rules\Container\RulesContainer;

/** @var array $params */

return [
    RuleFactoryInterface::class => [
        'class' => RulesContainer::class,
        '__construct()' => [
            'definitions' => $params['yiisoft/rbac-rules-container']['rules'],
            'validate' => $params['yiisoft/rbac-rules-container']['validate'],
        ],
    ],
];
