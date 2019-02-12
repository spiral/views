<?php
declare(strict_types=1);
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views;

use Spiral\Views\Exception\RenderException;

interface ViewInterface
{
    /**
     * Render view source using internal logic.
     *
     * @param array $data
     * @return string
     *
     * @throws RenderException
     */
    public function render(array $data = []): string;
}