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
use Spiral\Views\Engine\Native\NativeEngine;
use Spiral\Views\ViewContext;
use Spiral\Views\ViewLoader;

class NativeTest extends TestCase
{
    public function testGet()
    {
        $loader = new ViewLoader(new Files(), [
            'default' => __DIR__ . '/../fixtures/default',
            'other'   => __DIR__ . '/../fixtures/other',
        ]);

        $loader = $loader->withExtension('php');

        $engine = new NativeEngine(new Container());
        $engine = $engine->withLoader($loader);

        $engine->reset('other:view', new ViewContext());
        $engine->compile('other:view', new ViewContext());
        $view = $engine->get('other:view', $ctx = new ViewContext());

        $this->assertSame('other world', $view->render([]));
    }

    /**
     * @expectedException \Spiral\Views\Exception\EngineException
     */
    public function testGetNoLoader()
    {
        $engine = new NativeEngine(new Container());
        $engine->get('other:view', new ViewContext());
    }

    public function testRenderWithValue()
    {
        $loader = new ViewLoader(new Files(), [
            'default' => __DIR__ . '/../fixtures/default',
            'other'   => __DIR__ . '/../fixtures/other',

        ]);

        $loader = $loader->withExtension('php');

        $engine = new NativeEngine(new Container());
        $engine = $engine->withLoader($loader);

        $view = $engine->get('other:var', $ctx = new ViewContext());
        $this->assertSame('hello', $view->render(['value' => 'hello']));
    }

    /**
     * @expectedException \Spiral\Views\Exception\RenderException
     */
    public function testRenderException()
    {
        $loader = new ViewLoader(new Files(), [
            'default' => __DIR__ . '/../fixtures/default',
            'other'   => __DIR__ . '/../fixtures/other',

        ]);

        $loader = $loader->withExtension('php');

        $engine = new NativeEngine(new Container());
        $engine = $engine->withLoader($loader);

        $view = $engine->get('other:var', $ctx = new ViewContext());

        $view->render([]);
    }

    public function testRenderBufferWithValue()
    {
        $loader = new ViewLoader(new Files(), [
            'default' => __DIR__ . '/../fixtures/default',
            'other'   => __DIR__ . '/../fixtures/other',

        ]);

        $loader = $loader->withExtension('php');

        $engine = new NativeEngine(new Container());
        $engine = $engine->withLoader($loader);

        $view = $engine->get('other:buf', $ctx = new ViewContext());
        $this->assertSame('', $view->render(['value' => 'hello']));
    }

    /**
     * @expectedException \Spiral\Views\Exception\RenderException
     */
    public function testRenderBufferException()
    {
        $loader = new ViewLoader(new Files(), [
            'default' => __DIR__ . '/../fixtures/default',
            'other'   => __DIR__ . '/../fixtures/other',

        ]);

        $loader = $loader->withExtension('php');

        $engine = new NativeEngine(new Container());
        $engine = $engine->withLoader($loader);

        $view = $engine->get('other:buf', $ctx = new ViewContext());

        $view->render([]);
    }
}