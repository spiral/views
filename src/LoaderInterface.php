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
    // Namespace/viewName separator.
    const NS_SEPARATOR = ':';

    /**
     * Lock loader to specific file extension.
     *
     * @param string $extension
     * @return LoaderInterface
     */
    public function withExtension(string $extension): LoaderInterface;

    /**
     * Check if given view path has associated view in a loader. Path might include namespace prefix or extension.
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool;

    /**
     * Get source for given name. Path might include namespace prefix or extension.
     *
     * @param string $path
     * @return ViewSource
     *
     * @throws LoaderException
     */
    public function load(string $path): ViewSource;

    /**
     * Get names of all available views within this loader. Result will include namespace prefix and view name without
     * extension.
     *
     * @param string|null $namespace
     * @return array
     */
    public function list(string $namespace = null): array;
}