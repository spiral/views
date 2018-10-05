<?php
/**
 * spiral
 *
 * @author    Wolfy-J
 */

namespace Spiral\Views\Engine;

use Spiral\Views\EngineInterface;
use Spiral\Views\LoaderInterface;

/**
 * ViewEngine with ability to switch environment and loader.
 */
abstract class AbstractEngine implements EngineInterface
{
    const EXTENSIONS = [];

    /** @var LoaderInterface */
    protected $loader;

    /**
     * {@inheritdoc}
     */
    public function withLoader(LoaderInterface $loader): EngineInterface
    {
        $engine = clone $this;
        $engine->loader = $loader->withExtension(static::EXTENSIONS);

        return $engine;
    }
}