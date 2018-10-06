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
use Spiral\Core\Container\Autowire;
use Spiral\Views\Config\ViewsConfig;
use Spiral\Views\Context\ValueDependency;
use Spiral\Views\Engine\Native\NativeEngine;

class ConfigTest extends TestCase
{
    public function testCache()
    {
        $config = new ViewsConfig([
            'cache' => [
                'enable'    => true,
                'memory'    => true,
                'directory' => '/tmp'
            ]
        ]);

        $this->assertTrue($config->cacheEnabled());
        $this->assertTrue($config->cacheInMemory());
        $this->assertSame('/tmp/', $config->cacheDirectory());
    }

    public function testNamespace()
    {
        $config = new ViewsConfig([
            'namespaces' => [
                'default' => [__DIR__]
            ]
        ]);

        $this->assertSame([
            'default' => [__DIR__]
        ], $config->getNamespaces());
    }

    public function testEngines()
    {
        $container = new Container();

        $config = new ViewsConfig([
            'engines' => [new Autowire(NativeEngine::class)]
        ]);

        $this->assertInstanceOf(
            NativeEngine::class,
            $config->getEngines()[0]->resolve($container)
        );

        $config = new ViewsConfig([
            'engines' => [NativeEngine::class]
        ]);

        $this->assertInstanceOf(
            NativeEngine::class,
            $config->getEngines()[0]->resolve($container)
        );
    }

    public function testDependencies()
    {
        $container = new Container();
        $container->bindSingleton(
            'localeDependency',
            $dependency = new ValueDependency('locale', 'en', ['en', 'ru'])
        );

        $config = new ViewsConfig([
            'dependencies' => [
                'localeDependency'
            ]
        ]);

        $this->assertSame(
            $dependency,
            $config->getDependencies()[0]->resolve($container)
        );
    }
}