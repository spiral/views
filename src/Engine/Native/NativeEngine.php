<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views\Engine\Native;

use Psr\Container\ContainerInterface;
use Spiral\Views\ContextInterface;
use Spiral\Views\Engine\AbstractEngine;
use Spiral\Views\ViewInterface;

class NativeEngine extends AbstractEngine
{
    protected const EXTENSION = 'php';

    /** @var ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function compile(string $path, ContextInterface $context)
    {
        // doing nothing, native views can not be compiled
    }

    /**
     * @inheritdoc
     */
    public function reset(string $path, ContextInterface $context)
    {
        // doing nothing, native views can not be compiled
    }

    /**
     * @inheritdoc
     */
    public function get(string $path, ContextInterface $context): ViewInterface
    {
        return new NativeView($this->getLoader()->load($path), $this->container, $context);
    }
}