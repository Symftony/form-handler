<?php

namespace FormHandler\Tests\FormBundle\DependencyInjection\Compiler;

use FormHandler\FormBundle\DependencyInjection\Compiler\FormHandlerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class FormHandlerCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $containerBuilderMock;

    /**
     * @var FormHandlerCompilerPass
     */
    private $formHandlerCompilerPass;

    public function setUp()
    {
        $this->containerBuilderMock = $this->getMock(
            ContainerBuilder::class,
            ['findTaggedServiceIds', 'getDefinition', 'hasDefinition']
        );

        $this->formHandlerCompilerPass = new FormHandlerCompilerPass();
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Form factory expected for "form.handler" tagged services.
     */
    public function testProcessThrowLogicException()
    {
        $services = [
            'my_fake_form_handler1' => [],
        ];

        $this->containerBuilderMock->expects($this->once())
            ->method('hasDefinition')
            ->with($this->equalTo('form.factory'))
            ->willReturn(false);

        $this->containerBuilderMock->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with($this->equalTo('form.handler'))
            ->willReturn($services);

        $this->formHandlerCompilerPass->process($this->containerBuilderMock);
    }

    public function testProcess()
    {
        $services = [
            'my_fake_form_handler1' => [],
        ];

        $fakeFormHandler1Mock = $this->getMock(Definition::class);

        $this->containerBuilderMock->expects($this->once())
            ->method('hasDefinition')
            ->with($this->equalTo('form.factory'))
            ->willReturn(true);

        $this->containerBuilderMock->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with($this->equalTo('form.handler'))
            ->willReturn($services);

        $this->containerBuilderMock->expects($this->exactly(2))
            ->method('getDefinition')
            ->withConsecutive($this->equalTo(['form.factory']), $this->equalTo(['my_fake_form_handler1']))
            ->willReturnOnConsecutiveCalls('my_fake_form_factory_definition', $fakeFormHandler1Mock);

        $fakeFormHandler1Mock->expects($this->once())
            ->method('addMethodCall')
            ->with($this->equalTo('setFormFactory'), $this->equalTo(['my_fake_form_factory_definition']));

        $this->formHandlerCompilerPass->process($this->containerBuilderMock);
    }
}
