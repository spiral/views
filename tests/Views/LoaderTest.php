<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Files\Files;
use Spiral\Views\ViewLoader;

class LoaderTest extends TestCase
{
    /**
     * @expectedException \Spiral\Views\Exception\LoaderException
     */
    public function testExistsException()
    {
        $loader = new ViewLoader(new Files(), [
            'default' => __DIR__ . '/../fixtures/default'
        ]);

        $loader->exists("view");
    }

    /**
     * @expectedException \Spiral\Views\Exception\LoaderException
     */
    public function testListException()
    {
        $loader = new ViewLoader(new Files(), [
            'default' => __DIR__ . '/../fixtures/default'
        ]);

        $loader->list();
    }

    public function testExists()
    {
        $loader = new ViewLoader(new Files(), [
            'default' => __DIR__ . '/../fixtures/default'
        ]);

        $loader = $loader->withExtension('php');

        $this->assertTrue($loader->exists("view"));
        $this->assertTrue($loader->exists("inner/view"));
        $this->assertTrue($loader->exists("inner/partial/view"));
        $this->assertFalse($loader->exists("another"));

        $this->assertTrue($loader->exists("view.php"));

        $this->assertTrue($loader->exists("default:view"));
        $this->assertTrue($loader->exists("default:view.php"));

        $this->assertTrue($loader->exists("@default/view"));
        $this->assertTrue($loader->exists("@default/view.php"));

        $this->assertTrue($loader->exists("default:inner/partial/view"));
        $this->assertTrue($loader->exists("default:inner/partial/view.php"));

        $this->assertTrue($loader->exists("@default/inner/partial/view"));
        $this->assertTrue($loader->exists("@default/inner/partial/view.php"));
    }
}