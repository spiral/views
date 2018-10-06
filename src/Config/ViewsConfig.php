<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views\Config;

use Spiral\Core\Container\Autowire;
use Spiral\Core\InjectableConfig;
use Spiral\Views\Engine\Native\NativeEngine;
use Spiral\Views\Exception\ConfigException;

class ViewsConfig extends InjectableConfig
{
    const CONFIG = "views";

    /** @var array */
    protected $config = [
        'cache'        => [
            'enable'    => false,
            'memory'    => false,
            'directory' => '/tmp'
        ],
        'namespaces'   => [],
        'dependencies' => [],
        'engines'      => [
            NativeEngine::class
        ]
    ];

    /**
     * @return bool
     */
    public function cacheEnabled(): bool
    {
        return !empty($this->config['cache']['enable']) || !empty($this->config['cache']['enabled']);
    }

    /**
     * Enable in memory cache.
     *
     * @return bool
     */
    public function cacheInMemory(): bool
    {
        return !empty($this->config['cache']['memory']);
    }

    /**
     * @return string
     */
    public function cacheDirectory(): string
    {
        return rtrim($this->config['cache']['directory'], '/') . '/';
    }

    /**
     * Return all namespaces and their associated directories.
     *
     * @return array
     */
    public function getNamespaces(): array
    {
        return $this->config['namespaces'];
    }

    /**
     * Class names of all view dependencies.
     *
     * @return Autowire[]
     *
     * @throws ConfigException
     */
    public function getDependencies(): array
    {
        $dependencies = [];
        foreach ($this->config['dependencies'] as $dependency) {
            $dependencies[] = $this->wire($dependency);
        }

        return $dependencies;
    }

    /**
     * Get all the engines associated with view component.
     *
     * @return Autowire[]
     *
     * @throws ConfigException
     */
    public function getEngines(): array
    {
        $engines = [];
        foreach ($this->config['engines'] as $engine) {
            $engines[] = $this->wire($engine);
        }

        return $engines;
    }

    /**
     * @param mixed $item
     * @return Autowire
     *
     * @throws ConfigException
     */
    public function wire($item): Autowire
    {
        if ($item instanceof Autowire) {
            return $item;
        }

        if (is_string($item)) {
            return new Autowire($item);
        }

        throw new ConfigException("Invalid class reference in view config.");
    }
}