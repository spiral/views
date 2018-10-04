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
use Spiral\Views\Exception\EngineException;
use Spiral\Views\Exception\LoaderException;
use Spiral\Views\ViewInterface;

class NativeEngine extends AbstractEngine
{
    const EXTENSIONS = ['php'];

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
    public function get(string $path, ContextInterface $context): ViewInterface
    {
        try {
            return new NativeView($this->loader->getSource($path), $context, $this->container);
        } catch (LoaderException $e) {
            throw new EngineException($e->getMessage(), $e->getCode(), $e);
        }
    }
}