<?php

declare(strict_types=1);

namespace Yiisoft\Rbac\Rules\Container\Tests;

use PHPUnit\Framework\TestCase;
use stdClass;
use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\Rbac\Exception\RuleInterfaceNotImplementedException;
use Yiisoft\Rbac\Exception\RuleNotFoundException;
use Yiisoft\Rbac\RuleInterface;
use Yiisoft\Rbac\Rules\Container\RulesContainer;
use Yiisoft\Rbac\Rules\Container\Tests\Support\AuthorRule;
use Yiisoft\Test\Support\Container\SimpleContainer;

final class RulesContainerTest extends TestCase
{
    public function testCreate(): void
    {
        $rulesContainer = new RulesContainer(new SimpleContainer());

        $rule = $rulesContainer->create(AuthorRule::class);

        $this->assertInstanceOf(AuthorRule::class, $rule);
    }

    public function testNotFound(): void
    {
        $rulesContainer = new RulesContainer(new SimpleContainer());

        $this->expectException(RuleNotFoundException::class);
        $this->expectExceptionMessage('Rule "not-exists-rule" not found.');
        $this->expectExceptionCode(0);
        $rulesContainer->create('not-exists-rule');
    }

    public function testNotRuleInterface(): void
    {
        $rulesContainer = new RulesContainer(new SimpleContainer(), ['rule' => new stdClass()]);

        $this->expectException(RuleInterfaceNotImplementedException::class);
        $this->expectExceptionMessage('Rule "rule" must implement "' . RuleInterface::class . '".');
        $this->expectExceptionCode(0);
        $rulesContainer->create('rule');
    }

    public function testValidation(): void
    {
        $container = new SimpleContainer();
        $definitions = ['rule' => 42];

        $this->expectException(InvalidConfigException::class);
        new RulesContainer($container, $definitions);
    }

    public function testDisableValidation(): void
    {
        $rulesContainer = new RulesContainer(new SimpleContainer(), ['rule' => 42], false);

        $this->expectException(InvalidConfigException::class);
        $rulesContainer->create('rule');
    }
}
