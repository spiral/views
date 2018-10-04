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
     * Lock loader to limited set of extensions.
     *
     * @param array $extensions
     * @return LoaderInterface
     */
    public function withExtensions(array $extensions): LoaderInterface;

    /**
     * Check if given view path has associated view in a loader.
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool;

    /**
     * Get source for given name.
     *
     * @param string $path
     * @return ViewSource
     *
     * @throws LoaderException
     */
    public function getSource(string $path): ViewSource;

    /**
     * Get list of all available view paths specific to the loader. When no namespace is specified
     * loader must return all available views with inclusion of their namespace "namespace:viewName".
     *
     * @param string|null $namespace
     * @return array
     */
    public function getPaths(string $namespace = null): array;
}