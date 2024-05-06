<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px">
    </a>
    <h1 align="center">Yii RBAC Rules Container</h1>
    <br>
</p>

[![Latest Stable Version](https://poser.pugx.org/yiisoft/rbac-rules-container/v/stable.png)](https://packagist.org/packages/yiisoft/rbac-rules-container)
[![Total Downloads](https://poser.pugx.org/yiisoft/rbac-rules-container/downloads.png)](https://packagist.org/packages/yiisoft/rbac-rules-container)
[![Build status](https://github.com/yiisoft/rbac-rules-container/workflows/build/badge.svg)](https://github.com/yiisoft/rbac-rules-container/actions?query=workflow%3Abuild)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiisoft/rbac-rules-container/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/rbac-rules-container/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yiisoft/rbac-rules-container/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/rbac-rules-container/?branch=master)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Frbac-rules-container%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/rbac-rules-container/master)
[![static analysis](https://github.com/yiisoft/rbac-rules-container/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/rbac-rules-container/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/yiisoft/rbac-rules-container/coverage.svg)](https://shepherd.dev/github/yiisoft/rbac-rules-container)

This package is a factory for creating [Yii RBAC (Role-Based Access Control)](https://github.com/yiisoft/rbac) rules. It 
provides rules container based on [Yii Factory](https://github.com/yiisoft/factory) and uses 
[Yii Definitions](https://github.com/yiisoft/definitions) syntax. RBAC manager accepts rule as a name and parameters
and is unaware of its creation by design, delegating creation to rules container keeping responsibilities separation.

## Requirements

- PHP 8.1 or higher.

## Installation

The package could be installed with [Composer](https://getcomposer.org):

```shell
composer require yiisoft/rbac-rules-container
```

See [yiisoft/rbac](https://github.com/yiisoft/rbac) for RBAC package installation instructions.

## General usage

### Direct interaction with rules container

```php
use Psr\Container\ContainerInterface;
use Yiisoft\Rbac\Item;
use Yiisoft\Rbac\RuleInterface;
use Yiisoft\Rbac\Rules\Container\RulesContainer;

/**
 * Checks if user ID matches `authorID` passed via parameters.
 */
final class AuthorRule implements RuleInterface
{
    public function execute(string $userId, Item $item, array $parameters = []): bool
    {
        return $parameters['authorID'] === $userId;
    }
}

$rulesContainer = new RulesContainer(new MyContainer());
$rule = $rulesContainer->create(AuthorRule::class);
```

- `MyContainer` is a container for resolving dependencies and  must be an instance of 
`Psr\Container\ContainerInterface`. [Yii Dependency Injection](https://github.com/yiisoft/di) implementation also can 
be used.
- You can optionally set [definitions](https://github.com/yiisoft/definitions) and disable their validation if needed.

Basically, the arguments are the same as in [Yii Factory](https://github.com/yiisoft/factory). Please refer to its docs 
for more details.

Rules are created only once, then cached and reused for repeated calls.

```php
$rule = $rulesContainer->create(AuthorRule::class); // Returned from cache
```

### Using [Yii config](https://github.com/yiisoft/config)

```php
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;
use Yiisoft\Rbac\RuleFactoryInterface;
use Yiisoft\Rbac\Rules\Container\RulesContainer;

// Need to be moved to separate files accordingly
$params = [
    'yiisoft/rbac-rules-container' => [
        'rules' => ['author' => AuthorRule::class],
        'validate' => false,
    ],
];
$config = [
    RuleFactoryInterface::class => [
        'class' => RulesContainer::class,
        '__construct()' => [
            'definitions' => $params['yiisoft/rbac-rules-container']['rules'],
            'validate' => $params['yiisoft/rbac-rules-container']['validate'],
        ],
    ],
];          
$containerConfig = ContainerConfig::create()->withDefinitions($config); 
$container = new Container($containerConfig);
$rulesContainer = $container->get(RuleFactoryInterface::class);        
$rule = $rulesContainer->create('author');
```

## Documentation

- [Internals](docs/internals.md)

If you need help or have a question, the [Yii Forum](https://forum.yiiframework.com/c/yii-3-0/63) is a good place for that.
You may also check out other [Yii Community Resources](https://www.yiiframework.com/community).

## License

The Yii RBAC Rules Container is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).

## Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

## Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)
