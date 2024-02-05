<?php
declare(strict_types=1);

namespace Symftony\FormHandler\Tests\FormBundle;

use PHPUnit\Framework\TestCase;
use Symftony\FormHandler\FormBundle\DependencyInjection\Compiler\FormHandlerCompilerPass;
use Symftony\FormHandler\FormBundle\FormHandlerBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FormHandlerBundleTest extends TestCase
{
    private ContainerBuilder $containerBuilder;

    private FormHandlerBundle $formHandlerBundle;

    public function setUp(): void
    {
        $this->containerBuilder = new ContainerBuilder();

        $this->formHandlerBundle = new FormHandlerBundle();
    }

    public function testBuild()
    {
        $this->formHandlerBundle->build($this->containerBuilder);
        $containFormHandlerCompilerPass = false;

        foreach ($this->containerBuilder->getCompilerPassConfig()->getPasses() as $passe) {
            if ($passe instanceof FormHandlerCompilerPass) {
                $containFormHandlerCompilerPass = true;
                break;
            }
        }

        $this->assertTrue($containFormHandlerCompilerPass);
    }
}
