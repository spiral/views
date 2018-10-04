<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views;

use Spiral\Views\Exception\ViewException;

interface ViewsInterface
{
    /**
     * Get instance of view class associated with view path (path can include namespace).
     *
     * @param string $path
     * @return ViewInterface
     *
     * @throws ViewException
     */
    public function get(string $path): ViewInterface;
}