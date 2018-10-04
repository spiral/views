<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views\Traits;

use Spiral\Views\ContextInterface;
use Spiral\Views\ProcessorInterface;
use Spiral\Views\ViewSource;

trait ProcessorTrait
{
    /** @var ProcessorInterface[] */
    private $processors;

    /**
     * Process given view source using set of associated processors.
     *
     * @param ViewSource       $source
     * @param ContextInterface $context
     * @return ViewSource
     */
    private function process(ViewSource $source, ContextInterface $context): ViewSource
    {
        foreach ($this->processors as $processor) {
            $source = $source->withCode($processor->process($source, $context));
        }

        return $source;
    }
}