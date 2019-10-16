<?php

declare(strict_types=1);

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Views\Context\ValueDependency;
use Spiral\Views\ViewContext;

class ContextTest extends TestCase
{
    public function testResolveValue(): void
    {
        $context = new ViewContext();
        $context = $context->withDependency(new ValueDependency('test', 'value'));

        $this->assertSame('value', $context->resolveValue('test'));
    }

    /**
     * @expectedException \Spiral\Views\Exception\ContextException
     */
    public function testResolveValueException(): void
    {
        $context = new ViewContext();
        $context = $context->withDependency(new ValueDependency('test', 'value'));

        $this->assertSame('value', $context->resolveValue('other'));
    }
}
