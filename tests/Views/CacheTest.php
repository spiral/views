<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Core\Container;
use Spiral\Files\Files;
use Spiral\Views\Context\ValueDependency;
use Spiral\Views\ContextInterface;
use Spiral\Views\Engine\Native\NativeEngine;
use Spiral\Views\ViewCache;
use Spiral\Views\ViewContext;
use Spiral\Views\ViewInterface;
use Spiral\Views\ViewLoader;

class CacheTest extends TestCase
{
    public function testSimpleCache()
    {
        $ctx = new ViewContext();
        $cache = new ViewCache();

        $view = $this->getView($ctx, 'default:view');

        $cache->set($ctx, 'default:view', $view);
        $this->assertTrue($cache->has($ctx, 'default:view'));
        $this->assertSame($view, $cache->get($ctx, 'default:view'));
    }

    /**
     * @expectedException \Spiral\Views\Exception\CacheException
     */
    public function testGet()
    {
        $cache = new ViewCache();
        $cache->get(new ViewContext(), 'default:view');
    }

    public function testReset()
    {
        $ctx = new ViewContext();
        $ctx2 = $ctx->withDependency(new ValueDependency('test', 'value'));

        $cache = new ViewCache();

        $view = $this->getView($ctx, 'default:view');
        $view2 = $this->getView($ctx2, 'other:view');

        $cache->set($ctx, 'default:view', $view);
        $cache->set($ctx2, 'other:view', $view2);

        $this->assertTrue($cache->has($ctx, 'default:view'));
        $this->assertTrue($cache->has($ctx2, 'other:view'));

        $cache->reset($ctx);

        $this->assertFalse($cache->has($ctx, 'default:view'));
        $this->assertTrue($cache->has($ctx2, 'other:view'));
    }

    public function testResetAll()
    {
        $ctx = new ViewContext();
        $ctx2 = $ctx->withDependency(new ValueDependency('test', 'value'));

        $cache = new ViewCache();

        $view = $this->getView($ctx, 'default:view');
        $view2 = $this->getView($ctx2, 'other:view');

        $cache->set($ctx, 'default:view', $view);
        $cache->set($ctx2, 'other:view', $view2);

        $this->assertTrue($cache->has($ctx, 'default:view'));
        $this->assertTrue($cache->has($ctx2, 'other:view'));

        $cache->reset();

        $this->assertFalse($cache->has($ctx, 'default:view'));
        $this->assertFalse($cache->has($ctx2, 'other:view'));
    }

    public function testResetPath()
    {
        $ctx = new ViewContext();
        $cache = new ViewCache();

        $view = $this->getView($ctx, 'default:view');
        $view2 = $this->getView($ctx, 'other:view');

        $cache->set($ctx, 'default:view', $view);
        $cache->set($ctx, 'other:view', $view2);

        $this->assertTrue($cache->has($ctx, 'default:view'));
        $this->assertTrue($cache->has($ctx, 'other:view'));

        $cache->resetPath('other:view');

        $this->assertTrue($cache->has($ctx, 'default:view'));
        $this->assertFalse($cache->has($ctx, 'other:view'));
    }

    public function testContextValue()
    {
        $ctx = new ViewContext();
        $ctx = $ctx->withDependency(new ValueDependency('test', 'value'));

        $cache = new ViewCache();

        $view = $this->getView($ctx, 'default:view');

        $cache->set($ctx, 'default:view', $view);
        $this->assertTrue($cache->has($ctx, 'default:view'));

        $ctx = $ctx->withDependency(new ValueDependency('test', 'another'));
        $this->assertFalse($cache->has($ctx, 'default:view'));

        $ctx = $ctx->withDependency(new ValueDependency('test', 'value'));
        $this->assertTrue($cache->has($ctx, 'default:view'));
    }

    protected function getView(ContextInterface $context, string $path): ViewInterface
    {
        $loader = new ViewLoader(new Files(), [
            'default' => __DIR__ . '/../fixtures/default',
            'other'   => __DIR__ . '/../fixtures/other',
        ]);

        $engine = new NativeEngine(new Container());
        $engine = $engine->withLoader($loader->withExtension('php'));

        return $engine->get($path, $context);
    }
}