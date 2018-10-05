<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views;

use Spiral\Views\Exception\CacheException;

final class ViewCache
{
    /** @var array */
    private $cache = [];

    /**
     * @param ContextInterface|null $context
     */
    public function reset(ContextInterface $context = null)
    {
        if (empty($context)) {
            $this->cache = [];
            return;
        }

        unset($this->cache[$context->getID()]);
    }

    /**
     * @param ContextInterface $context
     * @param string           $path
     * @return bool
     */
    public function has(ContextInterface $context, string $path): bool
    {
        return isset($this->cache[$context->getID()][$path]);
    }

    /**
     * @param ContextInterface $context
     * @param string           $path
     * @param ViewInterface    $view
     */
    public function set(ContextInterface $context, string $path, ViewInterface $view)
    {
        $this->cache[$context->getID()][$path] = $view;
    }

    /**
     * @param ContextInterface $context
     * @param string           $path
     * @return ViewInterface
     *
     * @throws CacheException
     */
    public function get(ContextInterface $context, string $path): ViewInterface
    {
        if (!$this->has($context, $path)) {
            throw new CacheException("No cache is available for {$path}.");
        }

        return $this->cache[$context->getID()][$path];
    }
}