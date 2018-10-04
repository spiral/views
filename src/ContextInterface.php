<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views;

use Spiral\Views\Exception\ContextException;

interface ContextInterface
{
    /**
     * Calculated context id based on values of all dependencies.
     *
     * @return string
     */
    public function getID(): string;

    /**
     * Create environment with new binded dependency. Must not affect existed context dependencies.
     *
     * @param string   $dependency
     * @param callable $source
     * @return ContextInterface
     *
     * @throws ContextException
     */
    public function withDependency(string $dependency, callable $source): ContextInterface;

    /**
     * Get calculated dependency value.
     *
     * @param string $dependency
     * @return mixed
     *
     * @throws ContextException
     */
    public function resolveValue(string $dependency);
}