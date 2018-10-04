<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views;


class ViewManager
{
    private $cache;

    private $context;

    public function __construct(ViewCache $cache)
    {
        $this->cache = $cache;
    }

    public function get(string $path): ViewInterface
    {
        if ($this->cache->has($this->context, $path)) {
            return $this->cache->get($this->context, $path);
        }

        // find engine
        // get from engine
    }

    /**
     * @param string $path
     * @param array  $data
     * @return string
     */
    public function render(string $path, array $data = []): string
    {
        return $this->get($path)->render($data);
    }
}