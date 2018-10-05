<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views;

/**
 * Represents external value view cache depends on.
 */
interface DependencyInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * Get current dependency value.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Return list of all possible dependency values.
     *
     * @return array
     */
    public function getVariants(): array;
}