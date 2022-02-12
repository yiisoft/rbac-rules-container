<?php

declare(strict_types=1);

namespace Yiisoft\Rbac\Rules\Container\Tests;

use PHPUnit\Framework\TestCase;
use stdClass;
use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\Rbac\Exception\RuleInterfaceNotImplementedException;
use Yiisoft\Rbac\Exception\RuleNotFoundException;
use Yiisoft\Rbac\RuleInterface;
use Yiisoft\Rbac\Rules\Container\RuleContainer;
use Yiisoft\Rbac\Rules\Container\Tests\Support\AuthorRule;
use Yiisoft\Test\Support\Container\SimpleContainer;

final class RuleContainerTest extends TestCase
{
    public function testGet(): void
    {
        $ruleContainer = new RuleContainer(new SimpleContainer(), []);

        $rule = $ruleContainer->get(AuthorRule::class);

        $this->assertInstanceOf(AuthorRule::class, $rule);
    }

    public function testNotFound(): void
    {
        $ruleContainer = new RuleContainer(new SimpleContainer(), []);

        $this->expectException(RuleNotFoundException::class);
        $this->expectExceptionMessage('Rule "not-exists-rule" not found.');
        $this->expectExceptionCode(0);
        $ruleContainer->get('not-exists-rule');
    }

    public function testNotRuleInterface(): void
    {
        $ruleContainer = new RuleContainer(new SimpleContainer(), ['rule' => new stdClass()]);

        $this->expectException(RuleInterfaceNotImplementedException::class);
        $this->expectExceptionMessage('Rule "rule" should implement "' . RuleInterface::class . '".');
        $this->expectExceptionCode(0);
        $ruleContainer->get('rule');
    }

    public function testValidation(): void
    {
        $container = new SimpleContainer();
        $definitions = ['rule' => 42];

        $this->expectException(InvalidConfigException::class);
        $ruleContainer = new RuleContainer($container, $definitions);
    }

    public function testDisableValidation(): void
    {
        $ruleContainer = new RuleContainer(new SimpleContainer(), ['rule' => 42], false);

        $this->expectException(InvalidConfigException::class);
        $ruleContainer->get('rule');
    }
}
