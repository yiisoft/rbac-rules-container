<?php

declare(strict_types=1);

namespace Yiisoft\Rbac\Rules\Container;

use Psr\Container\ContainerInterface;
use Yiisoft\Definitions\Exception\CircularReferenceException;
use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\Definitions\Exception\NotInstantiableException;
use Yiisoft\Factory\Factory;
use Yiisoft\Factory\NotFoundException;
use Yiisoft\Rbac\Exception\RuleInterfaceNotImplementedException;
use Yiisoft\Rbac\Exception\RuleNotFoundException;
use Yiisoft\Rbac\RuleInterface;
use Yiisoft\Rbac\RuleFactoryInterface;

use function array_key_exists;

final class RulesContainer implements RuleFactoryInterface
{
    private Factory $factory;

    /**
     * @var RuleInterface[]
     * @psalm-var array<string,RuleInterface>
     */
    private array $instances = [];

    /**
     * @psalm-param array<string, mixed> $definitions
     *
     * @throws InvalidConfigException
     */
    public function __construct(ContainerInterface $container, array $definitions = [], bool $validate = true)
    {
        $this->factory = new Factory($container, $definitions, $validate);
    }

    /**
     * @param string $name
     *
     * @throws RuleInterfaceNotImplementedException
     * @throws RuleNotFoundException
     * @throws CircularReferenceException
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     *
     * @return RuleInterface
     */
    public function create(string $name): RuleInterface
    {
        if (!array_key_exists($name, $this->instances)) {
            try {
                $rule = $this->factory->create($name);
            } catch (NotFoundException $e) {
                throw new RuleNotFoundException($name, 0, $e);
            }

            if (!$rule instanceof RuleInterface) {
                throw new RuleInterfaceNotImplementedException($name);
            }

            $this->instances[$name] = $rule;
        }

        return $this->instances[$name];
    }
}
