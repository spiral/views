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

/**
 * Loads and locates view files associated with specific extensions.
 */
class ViewLoader implements LoaderInterface
{
    // Default view namespace
    public const DEFAULT_NAMESPACE = "default";

    /** @var FilesInterface */
    private $files;

    /** @var array */
    private $namespaces = [];

    /** @var array */

    private $parser;

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
        $this->parser = new PathParser($defaultNamespace);
    }

    /**
     * {@inheritdoc}
     */
    public function withExtension(string $extension): LoaderInterface
    {
        $loader = clone $this;

        $loader->parser = clone $loader->parser;
        $loader->parser->setExtension($extension);

        return $loader;
    }

    /**
     * {@inheritdoc}
     */
    public function exists(string $path): bool
    {
        if (!$this->parser->valid($path)) {
            return false;
        }

        $namespace = $this->parser->getNamespace($path);
        if (!isset($this->namespaces[$namespace])) {
            return false;
        }

        $basename = $this->parser->getBasename($path);
        foreach ($this->namespaces[$namespace] as $directory) {
            if ($this->files->exists(sprintf("%s%s", $directory, $basename))) {
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
        if (!$this->parser->valid($path)) {
            // todo: get error
        }

        list($namespace, $filename) = $this->parsePath($path);

        if (!isset($this->namespaces[$namespace])) {
            throw new LoaderException("Undefined view namespace '{$namespace}'");
        }

        foreach ($this->namespaces[$namespace] as $directory) {
            //Seeking for view filename
            if ($this->files->exists($directory . $filename)) {

                //Found view context
                return new ViewSource(
                    $directory . $filename,
                    $this->fetchName($filename),
                    $namespace
                );
            }
        }

        throw new LoaderException("Unable to locate view '{$filename}' in namespace '{$namespace}'");
    }

    /**
     * {@inheritdoc}
     */
    public function list(string $namespace = null): array
    {
        return [];
    }
}