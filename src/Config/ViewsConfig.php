<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views\Config;

use Spiral\Core\InjectableConfig;

class ViewsConfig extends InjectableConfig
{
    const CONFIG = "views";

    /** @var array */
    protected $config = [
        'cache' => [
            'enable'    => false,
            'directory' => '/tmp'
        ],
    ];

    /**
     * @return bool
     */
    public function cacheEnabled(): bool
    {
        return !empty($this->config['cache']['enable']) || !empty($this->config['cache']['enabled']);
    }

    /**
     * @return string
     */
    public function cacheDirectory(): string
    {
        return rtrim($this->config['cache']['directory'], '/') . '/';
    }
}