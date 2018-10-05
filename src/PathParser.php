<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views;


class PathParser
{
    const NS_SEPARATOR = LoaderInterface::NS_SEPARATOR;

    private $defaultNamespace;
    private $extension;

    public function __construct(string $defaultNamespace)
    {
        $this->defaultNamespace = $defaultNamespace;
    }

    public function setExtension(string $extension)
    {
        $this->extension = $extension;
    }

    public function getExtension(): array
    {
        return $this->extension;
    }

    public function valid(string $path): bool
    {
        return false;
    }

    public function getNamespace(string $path): string
    {
        return false;
    }


    public function getBasename(string $path): string
    {

    }

    public function getName(string $path): string
    {

    }

    /**
     * Fetch namespace and view name from the given path.
     *
     * @param string $path
     * @return array
     *
     * @throws LoaderException
     */
    protected function parsePath(string $path): array
    {
        // todo: verify the extension

        //Cutting extra symbols (see Twig)
        $filename = preg_replace(
            '#/{2,}#',
            '/',
            str_replace('\\', '/', (string)$path)
        );

        if (strpos($filename, '.') === false && !empty($this->extension)) {
            //Forcing default extension
            $filename .= '.' . $this->extension;
        }

        if (strpos($filename, self::NS_SEPARATOR) !== false) {
            return explode(self::NS_SEPARATOR, $filename);
        }

        //Twig like namespaces
        if (isset($filename[0]) && $filename[0] == '@') {
            if (($separator = strpos($filename, '/')) === false) {
                throw new LoaderException(sprintf(
                    'Malformed namespaced template name "%s" (expecting "@namespace/template_name").',
                    $path
                ));
            }

            $namespace = substr($filename, 1, $separator - 1);
            $filename = substr($filename, $separator + 1);

            return [$namespace, $filename];
        }

        //Let's force default namespace
        return [self::DEFAULT_NAMESPACE, $filename];
    }

    /**
     * Make sure view filename is OK. Same as in twig.
     *
     * @param string $name
     * @throws LoaderException
     */
    protected function validatePath(string $name)
    {
        if (false !== strpos($name, "\0")) {
            throw new LoaderException('A template name cannot contain NUL bytes');
        }

        $name = ltrim($name, '/');
        $parts = explode('/', $name);
        $level = 0;
        foreach ($parts as $part) {
            if ('..' === $part) {
                --$level;
            } elseif ('.' !== $part) {
                ++$level;
            }

            if ($level < 0) {
                throw new LoaderException(sprintf(
                    'Looks like you try to load a template outside configured directories (%s)',
                    $name
                ));
            }
        }
    }

    /**
     * Resolve view name based on filename (depends on current extension settings).
     *
     * @param string $filename
     * @return string
     */
    private function fetchName(string $filename): string
    {
        if (empty($this->extension)) {
            return $filename;
        }

        return substr($filename, 0, -1 * (1 + strlen($this->extension)));
    }
}