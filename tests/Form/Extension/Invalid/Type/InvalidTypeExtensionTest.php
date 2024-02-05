<?php
declare(strict_types=1);

namespace Symftony\FormHandler\Tests\Form\Extension\Invalid\Type;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symftony\FormHandler\Form\Extension\Invalid\Type\InvalidTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvalidTypeExtensionTest extends TestCase
{
    use ProphecyTrait;

    private OptionsResolver|ObjectProphecy $optionsResolverMock;

    private InvalidTypeExtension $invalidTypeExtension;

    public function setUp(): void
    {
        $this->optionsResolverMock = $this->prophesize(OptionsResolver::class);

        $this->invalidTypeExtension = new InvalidTypeExtension();
    }

    public function testConfigureOptions()
    {
        $this->optionsResolverMock
            ->setDefaults([
                'handler_invalid' => false,
            ])
            ->willReturn($this->optionsResolverMock->reveal())
            ->shouldBeCalled();

        $this->invalidTypeExtension->configureOptions($this->optionsResolverMock->reveal());
    }

    public function testGetExtendedType()
    {
        $this->assertEquals([FormType::class], $this->invalidTypeExtension->getExtendedTypes());
    }
}
