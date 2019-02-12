<?php
declare(strict_types=1);
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views\Exception;

class RenderException extends ViewException
{
    /**
     * {@inheritdoc}
     */
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct($previous->getMessage(), $previous->getCode(), $previous);
        $this->file = $previous->getFile();
        $this->line = $previous->getLine();
    }
}