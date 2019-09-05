<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Views\Loader;

use Spiral\Views\Exception\PathException;
use Spiral\Views\LoaderInterface;

/**
 * Parse view path and return name chunks (namespace, name, basename).
 */
final class PathParser
{
    /** @var string */
    private $defaultNamespace;

    /** @var string|null */
    private $extension;

    /**
     * @param string $defaultNamespace
     * @param string $extension
     */
    public function __construct(string $defaultNamespace, string $extension)
    {
        $this->defaultNamespace = $defaultNamespace;
        $this->extension = $extension;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Check if filename matches to expected extension.
     *
     * @param string $filename
     * @return bool
     */
    public function match(string $filename): bool
    {
        $extension = substr($filename, -strlen($this->extension) - 1);
        return strtolower($extension) === ".{$this->extension}";
    }

    /**
     * Parse view path and extract name, namespace and basename information.
     *
     * @param string $path
     * @return null|ViewPath
     *
     * @throws PathException
     */
    public function parse(string $path): ?ViewPath
    {
        $this->validatePath($path);

        //Cutting extra symbols (see Twig)
        $filename = preg_replace(
            '#/{2,}#',
            '/',
            str_replace('\\', '/', (string)$path)
        );

        $namespace = $this->defaultNamespace;
        if (strpos($filename, '.') === false) {
            //Force default extension
            $filename .= '.' . $this->extension;
        } elseif (!$this->match($filename)) {
            return null;
        }

        if (strpos($filename, LoaderInterface::NS_SEPARATOR) !== false) {
            list($namespace, $filename) = explode(LoaderInterface::NS_SEPARATOR, $filename);
        }

        //Twig like namespaces
        if (isset($filename[0]) && $filename[0] == '@') {
            $separator = strpos($filename, '/');
            if ($separator === false) {
                throw new PathException(sprintf('Malformed view path"%s" (expecting "@namespace/name").', $path));
            }

            $namespace = substr($filename, 1, $separator - 1);
            $filename = substr($filename, $separator + 1);
        }

        return new ViewPath(
            $namespace,
            $this->fetchName($filename),
            $filename
        );
    }

    /**
     * Get view name from given filename.
     *
     * @param string $filename
     * @return null|string
     */
    public function fetchName(string $filename): ?string
    {
        return str_replace('\\', '/', substr($filename, 0, -1 * (1 + strlen($this->extension))));
    }

    /**
     * Make sure view filename is OK. Same as in twig.
     *
     * @param string $path
     * @throws PathException
     */
    private function validatePath(string $path)
    {
        if (empty($path)) {
            throw new PathException('A view path is empty');
        }

        if (false !== strpos($path, "\0")) {
            throw new PathException('A view path cannot contain NUL bytes');
        }

        $path = ltrim($path, '/');
        $parts = explode('/', $path);
        $level = 0;

        foreach ($parts as $part) {
            if ('..' === $part) {
                --$level;
            } elseif ('.' !== $part) {
                ++$level;
            }

            if ($level < 0) {
                throw new PathException(sprintf(
                    'Looks like you try to load a view outside configured directories (%s)',
                    $path
                ));
            }
        }
    }
}