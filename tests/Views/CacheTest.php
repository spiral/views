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