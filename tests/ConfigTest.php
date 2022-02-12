<?php

declare(strict_types=1);

namespace Yiisoft\Rbac\Rules\Container\Tests;

use PHPUnit\Framework\TestCase;

use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;
use Yiisoft\Rbac\Rules\Container\RulesContainer;
use Yiisoft\Rbac\Rules\Container\Tests\Support\AuthorRule;
use Yiisoft\Rbac\RulesFactoryInterface;

use function dirname;

final class ConfigTest extends TestCase
{
    public function testBase(): void
    {
        $container = $this->createContainer();

        $rulesContainer = $container->get(RulesFactoryInterface::class);

        $this->assertInstanceOf(RulesContainer::class, $rulesContainer);
    }

    public function testDisableValidation(): void
    {
        $params = $this->getParams();
        $params['yiisoft/rbac-rules-container']['rules'] = ['rule' => 42];
        $params['yiisoft/rbac-rules-container']['validate'] = false;
        $container = $this->createContainer($params);

        $rulesContainer = $container->get(RulesFactoryInterface::class);

        $this->expectException(InvalidConfigException::class);
        $rulesContainer->create('rule');
    }

    public function testRules(): void
    {
        $params = $this->getParams();
        $params['yiisoft/rbac-rules-container']['rules'] = ['rule' => AuthorRule::class];
        $container = $this->createContainer($params);

        $rulesContainer = $container->get(RulesFactoryInterface::class);
        $rule = $rulesContainer->create('rule');

        $this->assertInstanceOf(AuthorRule::class, $rule);
    }

    private function createContainer(?array $params = null): Container
    {
        return new Container(
            ContainerConfig::create()->withDefinitions(
                $this->getCommonDefinitions($params)
            )
        );
    }

    private function getCommonDefinitions(?array $params = null): array
    {
        if ($params === null) {
            $params = $this->getParams();
        }
        return require dirname(__DIR__) . '/config/common.php';
    }

    private function getParams(): array
    {
        return require dirname(__DIR__) . '/config/params.php';
    }
}
