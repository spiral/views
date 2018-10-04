<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views\Engine\Native;

use Psr\Container\ContainerInterface;
use Spiral\Core\ContainerScope;
use Spiral\Views\ContextInterface;
use Spiral\Views\Exception\RenderException;
use Spiral\Views\ViewInterface;
use Spiral\Views\ViewSource;

class NativeView implements ViewInterface
{
    /*** @var ViewSource */
    protected $view;

    /** @var ContextInterface */
    protected $context;

    /** @var ContainerInterface */
    protected $container = null;

    /**
     * @param ViewSource         $view
     * @param ContextInterface   $context
     * @param ContainerInterface $container
     */
    public function __construct(ViewSource $view, ContextInterface $context, ContainerInterface $container)
    {
        $this->view = $view;
        $this->context = $context;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function render(array $data = []): string
    {
        ob_start();
        $__outputLevel__ = ob_get_level();

        try {
            ContainerScope::runScope($this->container, function () use ($data) {
                extract($data, EXTR_OVERWRITE);
                // render view in context and output buffer scope, context can be accessed using $this->context
                require $this->view->getFilename();
            });
        } catch (\Throwable $e) {
            ob_end_clean();
            throw new RenderException($e);
        } finally {
            //Closing all nested buffers
            while (ob_get_level() > $__outputLevel__) {
                ob_end_clean();
            }
        }

        return ob_get_clean();
    }
}