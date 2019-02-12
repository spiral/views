<?php
declare(strict_types=1);
/**
 * spiral
 *
 * @author    Wolfy-J
 */

namespace Spiral\Views\Engine;

use Spiral\Views\EngineInterface;
use Spiral\Views\Exception\EngineException;
use Spiral\Views\LoaderInterface;

/**
 * ViewEngine with ability to switch environment and loader.
 */
abstract class AbstractEngine implements EngineInterface
{
    protected const EXTENSION = '';

    /** @var LoaderInterface */
    protected $loader;

    /**
     * {@inheritdoc}
     */
    public function withLoader(LoaderInterface $loader): EngineInterface
    {
        $engine = clone $this;
        $engine->loader = $loader->withExtension(static::EXTENSION);

        return $engine;
    }

    /**
     * {@inheritdoc}
     */
    public function getLoader(): LoaderInterface
    {
        if (empty($this->loader)) {
            throw new EngineException("No associated loader found.");
        }

        return $this->loader;
    }
}