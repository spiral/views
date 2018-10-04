<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views;


use Spiral\Views\Exception\LoaderException;

interface LoaderInterface
{
    /**
     * Get source for given name.
     *
     * @param string $path
     * @return ViewSource
     *
     * @throws LoaderException
     */
    public function getSource(string $path): ViewSource;
}