<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views;

use Spiral\Files\FilesInterface;
use Spiral\Views\Exception\LoaderException;
use Spiral\Views\Loader\PathParser;
use Spiral\Views\Loader\ViewPath;

/**
 * Loads and locates view files associated with specific extensions.
 */
class ViewLoader implements LoaderInterface
{
    /** @var FilesInterface */
    private $files;

    /** @var PathParser|null */
    private $parser = null;

    /** @var array */
    private $namespaces = [];

    /** @var string */
    private $defaultNamespace = self::DEFAULT_NAMESPACE;

    /**
     * @param FilesInterface $files
     * @param array          $namespaces
     * @param string         $defaultNamespace
     */
    public function __construct(
        FilesInterface $files,
        array $namespaces,
        string $defaultNamespace = self::DEFAULT_NAMESPACE
    ) {
        $this->files = $files;
        $this->namespaces = $namespaces;
        $this->defaultNamespace = $defaultNamespace;
    }

    /**
     * {@inheritdoc}
     */
    public function withExtension(string $extension): LoaderInterface
    {
        $loader = clone $this;
        $loader->parser = new PathParser($this->defaultNamespace, $extension);

        return $loader;
    }

    /**
     * {@inheritdoc}
     *
     * @param string   $filename
     * @param ViewPath $parsed
     */
    public function exists(string $path, string &$filename = null, ViewPath &$parsed = null): bool
    {
        if (empty($this->parser)) {
            throw new LoaderException("Unable to locate view source, no extension has been associated.");
        }

        $parsed = $this->parser->parse($path);
        if (empty($parsed)) {
            return false;
        }

        if (!isset($this->namespaces[$parsed->getNamespace()])) {
            return false;
        }

        foreach ((array)$this->namespaces[$parsed->getNamespace()] as $directory) {
            $directory = $this->files->normalizePath($directory, true);
            if ($this->files->exists(sprintf("%s%s", $directory, $parsed->getBasename()))) {
                $filename = sprintf("%s%s", $directory, $parsed->getBasename());

                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function load(string $path): ViewSource
    {
        if (!$this->exists($path, $filename, $parsed)) {
            throw new LoaderException("Unable to load view `$path`, file does not exists.");
        }

        /** @var ViewPath $parsed */
        return new ViewSource($filename, $parsed->getNamespace(), $parsed->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function list(string $namespace = null): array
    {
        if (empty($this->parser)) {
            throw new LoaderException("Unable to list view sources, no extension has been associated.");
        }

        $result = [];
        foreach ($this->namespaces as $ns => $directories) {
            if (!empty($namespace) && $namespace != $ns) {
                continue;
            }

            foreach ((array)$directories as $directory) {
                $files = $this->files->getFiles($directory);
                foreach ($files as $filename) {
                    if (!$this->parser->match($filename)) {
                        // does not belong to this loader
                        continue;
                    }

                    $name = $this->parser->fetchName($this->files->relativePath($filename,
                        $directory));
                    $result[] = sprintf("%s%s%s", $ns, self::NS_SEPARATOR, $name);
                }
            }
        }

        return $result;
    }
}