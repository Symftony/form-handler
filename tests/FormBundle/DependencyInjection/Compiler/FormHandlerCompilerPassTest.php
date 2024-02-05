<?php
declare(strict_types=1);

namespace Symftony\FormHandler\Tests\FormBundle\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symftony\FormHandler\FormBundle\DependencyInjection\Compiler\FormHandlerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class FormHandlerCompilerPassTest extends TestCase
{
    use ProphecyTrait;

    private ContainerBuilder $containerBuilder;

    private FormHandlerCompilerPass $formHandlerCompilerPass;

    public function setUp(): void
    {
        $this->containerBuilder = new ContainerBuilder();

        $this->formHandlerCompilerPass = new FormHandlerCompilerPass();
    }

    public function testProcessThrowLogicException()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Form factory expected for "form.handler" tagged services.');

        $this->containerBuilder->addDefinitions([
            'my_custom_form_handler' => (new Definition())->addTag('form.handler')
        ]);

        $this->formHandlerCompilerPass->process($this->containerBuilder);
    }

    public function testProcess()
    {
        $formFactoryDefinition = new Definition();

        $def = new Definition();
        $def->addTag('form.handler');

        $this->containerBuilder->addDefinitions([
            'form.factory' => $formFactoryDefinition,
            'my_custom_form_handler' => $def,
        ]);

        $this->formHandlerCompilerPass->process($this->containerBuilder);

        $this->assertSame([
            ['setFormFactory', [$formFactoryDefinition]]
        ], $def->getMethodCalls());
    }
}
