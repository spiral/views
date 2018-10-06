<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views;


use Spiral\Views\Exception\ViewException;

class ViewManager
{
    /** @var ViewContext */
    private $context;

    /** @var LoaderInterface */
    private $loader;

    /** @var ViewCache|null */
    private $cache;

    /** @var EngineInterface[] */
    private $engines;

    /**
     * @param LoaderInterface $loader
     * @param ViewCache|null  $cache
     */
    public function __construct(LoaderInterface $loader, ViewCache $cache = null)
    {
        $this->context = new ViewContext();
        $this->loader = $loader;
        $this->cache = $cache;
    }

    /**
     * Attach new view context dependency.
     *
     * @param DependencyInterface $dependency
     */
    public function addDependency(DependencyInterface $dependency)
    {
        $this->context = $this->context->withDependency($dependency);
    }

    /**
     * Attach new view engine.
     *
     * @param EngineInterface $engine
     */
    public function addEngine(EngineInterface $engine)
    {
        $this->engines[] = $engine->withLoader($this->loader);
    }

    /**
     * Get all associated view engines.
     *
     * @return array
     */
    public function getEngines(): array
    {
        return $this->engines;
    }

    /**
     * Compile one of multiple cache versions for a given view path.
     *
     * @param string                $path
     * @param ContextInterface|null $context
     *
     * @throws ViewException
     */
    public function compile(string $path, ContextInterface $context = null)
    {
        if (!empty($this->cache)) {
            $this->cache->resetPath($path);
        }

        $engine = $this->detectEngine($path);
        if (!empty($context)) {
            $engine->compile($path, $context);

            return;
        }

        // Rotate all possible context variants and warm up cache
        $generator = new ContextGenerator($this->context);
        foreach ($generator->generate() as $context) {
            $engine->compile($path, $context);
        }
    }

    /**
     * Get view from one of the associated engines.
     *
     * @param string $path
     * @return ViewInterface
     *
     * @throws ViewException
     */
    public function get(string $path): ViewInterface
    {
        if (!empty($this->cache) && $this->cache->has($this->context, $path)) {
            return $this->cache->get($this->context, $path);
        }

        $view = $this->detectEngine($path)->get($path, $this->context);

        if (!empty($this->cache)) {
            $this->cache->set($this->context, $path, $view);
        }

        return $view;
    }

    /**
     * @param string $path
     * @param array  $data
     * @return string
     *
     * @throws ViewException
     */
    public function render(string $path, array $data = []): string
    {
        return $this->get($path)->render($data);
    }

    /**
     * @param string $path
     * @return EngineInterface
     *
     * @throws ViewException
     */
    protected function detectEngine(string $path): EngineInterface
    {
        foreach ($this->engines as $engine) {
            if ($engine->getLoader()->exists($path)) {
                return $engine;
            }
        }

        throw new ViewException("Unable to detect view engine for `{$path}`.");
    }
}