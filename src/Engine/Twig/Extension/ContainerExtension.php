<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views\Engine\Twig\Extension;

use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;

/**
 * Provides access to container bindings using `get` alias.
 */
class ContainerExtension extends AbstractExtension
{
    /** @var ContainerInterface */
    protected $container = null;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [new \Twig_SimpleFunction('get', [$this->container, 'get'])];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'get';
    }
}
