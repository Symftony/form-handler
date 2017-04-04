<?php

namespace FormHandler\Tests\FormBundle;

use FormHandler\FormBundle\DependencyInjection\Compiler\FormHandlerCompilerPass;
use FormHandler\FormBundle\FormHandlerBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FormBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $containerBuilderMock;

    /**
     * @var FormHandlerBundle
     */
    private $formHandlerBundle;

    public function setUp()
    {
        $this->containerBuilderMock = $this->getMock(ContainerBuilder::class, ['addCompilerPass']);

        $this->formHandlerBundle = new FormHandlerBundle();
    }

    public function testBuild()
    {
        $this->containerBuilderMock->expects($this->once())
            ->method('addCompilerPass')
            ->with($this->isInstanceOf(FormHandlerCompilerPass::class));

        $this->formHandlerBundle->build($this->containerBuilderMock);
    }
}
