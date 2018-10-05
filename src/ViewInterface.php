<?php
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
     * Context associated with view object.
     *
     * @return ContextInterface
     */
    public function getContext(): ContextInterface;

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