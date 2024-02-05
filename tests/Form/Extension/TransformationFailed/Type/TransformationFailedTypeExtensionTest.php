<?php
declare(strict_types=1);

namespace Symftony\FormHandler\Tests\Form\Extension\TransformationFailed\Type;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symftony\FormHandler\Form\Extension\TransformationFailed\Type\TransformationFailedTypeExtension;

class TransformationFailedTypeExtensionTest extends TestCase
{
    use ProphecyTrait;

    private OptionsResolver|ObjectProphecy $optionsResolverMock;

    private TransformationFailedTypeExtension $notSubmittedTypeExtension;

    public function setUp(): void
    {
        $this->optionsResolverMock = $this->prophesize(OptionsResolver::class);

        $this->notSubmittedTypeExtension = new TransformationFailedTypeExtension();
    }

    public function testConfigureOptions()
    {
        $this->optionsResolverMock
            ->setDefaults([
                'handler_transformation_failed' => false,
            ])
            ->willReturn($this->optionsResolverMock->reveal())
            ->shouldBeCalled();

        $this->notSubmittedTypeExtension->configureOptions($this->optionsResolverMock->reveal());
    }

    public function testGetExtendedType()
    {
        $this->assertEquals([FormType::class], $this->notSubmittedTypeExtension->getExtendedTypes());
    }
}
