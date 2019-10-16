<?php

declare(strict_types=1);

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Views\Loader\PathParser;
use Spiral\Views\ViewLoader;

class LoaderTest extends TestCase
{
    /**
     * @expectedException \Spiral\Views\Exception\LoaderException
     */
    public function testExistsException(): void
    {
        $loader = new ViewLoader([
            'default' => __DIR__ . '/../fixtures/default'
        ]);

        $loader->exists('view');
    }

    /**
     * @expectedException \Spiral\Views\Exception\LoaderException
     */
    public function testListException(): void
    {
        $loader = new ViewLoader([
            'default' => __DIR__ . '/../fixtures/default'
        ]);

        $loader->list();
    }

    /**
     * @expectedException \Spiral\Views\Exception\LoaderException
     */
    public function testLoadException(): void
    {
        $loader = new ViewLoader([
            'default' => __DIR__ . '/../fixtures/default'
        ]);

        $loader->load('view');
    }

    public function testExists(): void
    {
        $loader = new ViewLoader([
            'default' => __DIR__ . '/../fixtures/default'
        ]);

        $loader = $loader->withExtension('php');

        $this->assertFalse($loader->exists('another'));
        $this->assertFalse($loader->exists('inner/file.twig'));
        $this->assertFalse($loader->exists('inner/file'));

        $this->assertTrue($loader->exists('view'));
        $this->assertTrue($loader->exists('inner/view'));
        $this->assertTrue($loader->exists('inner/partial/view'));

        $this->assertTrue($loader->exists('view.php'));

        $this->assertTrue($loader->exists('default:view'));
        $this->assertTrue($loader->exists('default:view.php'));

        $this->assertTrue($loader->exists('@default/view'));
        $this->assertTrue($loader->exists('@default/view.php'));

        $this->assertTrue($loader->exists('default:inner/partial/view'));
        $this->assertTrue($loader->exists('default:inner/partial/view.php'));

        $this->assertTrue($loader->exists('@default/inner/partial/view'));
        $this->assertTrue($loader->exists('@default/inner/partial/view.php'));
    }

    public function testList(): void
    {
        $loader = new ViewLoader([
            'default' => __DIR__ . '/../fixtures/default'
        ]);

        $loader = $loader->withExtension('php');
        $files = $loader->list();

        $this->assertContains('default:view', $files);
        $this->assertContains('default:inner/view', $files);
        $this->assertContains('default:inner/partial/view', $files);
        $this->assertNotContains('default:inner/file', $files);
    }

    /**
     * @expectedException \Spiral\Views\Exception\LoaderException
     */
    public function testLoadNotFound(): void
    {
        $loader = new ViewLoader([
            'default' => __DIR__ . '/../fixtures/default'
        ]);

        $loader = $loader->withExtension('php');

        $loader->load('inner/file');
    }

    /**
     * @expectedException \Spiral\Views\Exception\PathException
     */
    public function testBadPath(): void
    {
        $parser = new PathParser('default', 'php');
        $parser->parse('@namespace');
    }

    /**
     * @expectedException \Spiral\Views\Exception\PathException
     */
    public function testEmptyPath(): void
    {
        $parser = new PathParser('default', 'php');
        $parser->parse('');
    }

    /**
     * @expectedException \Spiral\Views\Exception\PathException
     */
    public function testInvalidPath(): void
    {
        $parser = new PathParser('default', 'php');
        $parser->parse("hello\0");
    }

    /**
     * @expectedException \Spiral\Views\Exception\PathException
     */
    public function testExternalPath(): void
    {
        $parser = new PathParser('default', 'php');
        $parser->parse('../../../index.php');
    }

    public function testLoad(): void
    {
        $loader = new ViewLoader([
            'default' => __DIR__ . '/../fixtures/default'
        ]);

        $loader = $loader->withExtension('php');

        $source = $loader->load('inner/partial/view');
        $this->assertNotNull($source);

        $this->assertSame('inner/partial/view', $source->getName());
        $this->assertSame('default', $source->getNamespace());
        $this->assertFileExists($source->getFilename());

        $this->assertSame('hello inner partial world', $source->getCode());

        $newSource = $source->withCode('new code');

        $this->assertSame('new code', $newSource->getCode());
        $this->assertSame('hello inner partial world', $source->getCode());
    }

    public function testMultipleNamespaces(): void
    {
        $loader = new ViewLoader([
            'default' => __DIR__ . '/../fixtures/default',
            'other'   => __DIR__ . '/../fixtures/other',

        ]);

        $loader = $loader->withExtension('php');

        $this->assertTrue($loader->exists('other:view'));
        $this->assertFalse($loader->exists('non-existed:view'));

        $files = $loader->list();
        $this->assertContains('default:view', $files);
        $this->assertContains('other:view', $files);

        $files = $loader->list('other');
        $this->assertCount(4, $files);
        $this->assertContains('other:view', $files);
    }
}
