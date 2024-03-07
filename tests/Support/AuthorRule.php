<?php

declare(strict_types=1);

namespace Yiisoft\Rbac\Rules\Container\Tests\Support;

use Yiisoft\Rbac\Item;
use Yiisoft\Rbac\RuleContext;
use Yiisoft\Rbac\RuleInterface;

/**
 * Checks if user ID matches `authorID` passed via parameters.
 */
final class AuthorRule implements RuleInterface
{
    public function execute(?string $userId, Item $item, RuleContext $context): bool
    {
        return $context->getParameterValue('authorId') === $userId;
    }
}
