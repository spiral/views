<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views;

use Psr\Container\ContainerInterface;
use Spiral\Views\Exception\ContextException;

/**
 * Declares set of dependencies for view environment.
 *
 * Attention, dependency set is stated as immutable, THOUGHT calculated values DO depend on
 * container and might change in application lifetime.
 */
final class ViewContext implements ContextInterface
{
    /** @var array */
    private $dependencies = [];

    /** @var ContainerInterface */
    private $container = null;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getID(): string
    {
        $calculated = '';
        foreach ($this->dependencies as $dependency => $source) {
            $calculated .= "[{$dependency}={$this->resolveValue($dependency)}]";
        }

        return md5($calculated);
    }

    /**
     * {@inheritdoc}
     *
     * You can add dependency to a function, closure, or callable pair where first argument is
     * binding name (resolved thought container).
     */
    // todo: dependency interface
    public function withDependency(string $dependency, callable $source): ContextInterface
    {
        $environment = clone $this;
        $environment->dependencies[$dependency] = $source;

        return $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveValue(string $dependency)
    {
        if (!isset($this->dependencies[$dependency])) {
            throw new ContextException("Undefined context dependency '{$dependency}'.");
        }

        $source = $this->dependencies[$dependency];

        //Let's resolve using container
        if (is_array($source) && is_string($source[0])) {
            $source[0] = $this->container->get($source[0]);
            $this->dependencies[$dependency] = $source;
        }

        return call_user_func($source);
    }
}