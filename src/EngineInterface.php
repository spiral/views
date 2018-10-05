<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views;

use Spiral\Views\Exception\EngineException;
use Spiral\Views\Exception\LoaderException;

interface EngineInterface
{
    /**
     * Configure view engine with new loader.
     *
     * @param LoaderInterface $loader
     * @return EngineInterface
     */
    public function withLoader(LoaderInterface $loader): EngineInterface;

    /**
     * Compile (and reset cache) for the given view path in a provided context. This method must be called
     * each time view must be re-compiled.
     *
     * @param string           $path
     * @param ContextInterface $context
     *
     * @throws EngineException
     * @throws LoaderException
     */
    public function compile(string $path, ContextInterface $context);

    /**
     * Get instance of view class associated with view path (path can include namespace). Engine must attempt
     * to use existed cache if such presented (or compile view directly if cache has been disabled).
     *
     * @param string           $path
     * @param ContextInterface $context
     * @return ViewInterface
     *
     * @throws EngineException
     * @throws LoaderException
     */
    public function get(string $path, ContextInterface $context): ViewInterface;
}