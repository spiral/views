<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Views;

use PHPUnit\Framework\TestCase;
use Spiral\Files\Files;
use Spiral\Views\Context\ValueDependency;
use Spiral\Views\Processor\ContextProcessor;
use Spiral\Views\Traits\ProcessorTrait;

class ContextProcessorTest extends TestCase
{
    use ProcessorTrait;

    public function testProcessContext()
    {
        $this->processors[] = new ContextProcessor();

        $source = $this->getSource('other:inject');

        $this->assertSame('hello @{name|default}', $source->getCode());

        $ctx = new ViewContext();
        $source2 = $this->process($source, $ctx->withDependency(new ValueDependency('name', 'Bobby')));
        $this->assertSame('hello Bobby', $source2->getCode());
    }

    /**
     * @expectedException \Spiral\Views\Exception\ContextException
     */
    public function testProcessContextException()
    {
        $this->processors[] = new ContextProcessor();

        $source = $this->getSource('other:inject');

        $this->assertSame('hello @{name|default}', $source->getCode());

        $ctx = new ViewContext();
        $this->process($source, $ctx);
    }

    protected function getSource(string $path): ViewSource
    {
        $loader = new ViewLoader(new Files(), [
            'default' => __DIR__ . '/../fixtures/default',
            'other'   => __DIR__ . '/../fixtures/other',
        ]);

        return $loader->withExtension('php')->load($path);
    }
}