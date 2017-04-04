<?php

namespace FormHandler\Tests\Form\Extension\Invalid\Type;

use FormHandler\Form\Extension\TransformationFailed\Type\TransformationFailedTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransformationFailedTypeExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OptionsResolver
     */
    private $optionsResolverMock;

    /**
     * @var TransformationFailedTypeExtension
     */
    private $transformationFailedTypeExtension;

    public function setUp()
    {
        $this->optionsResolverMock = $this->getMock(OptionsResolver::class);

        $this->transformationFailedTypeExtension = new TransformationFailedTypeExtension();
    }

    public function testConfigureOptions()
    {
        $this->optionsResolverMock->expects($this->once())
            ->method('hasDefault')
            ->with($this->equalTo('handler_invalid_fatal'))
            ->willReturn(true);

        $this->optionsResolverMock->expects($this->once())
            ->method('setDefaults')
            ->with($this->equalTo([
                'handler_transformation_failed_fatal' => false,
                'handler_transformation_failed_data' => null,
            ]));

        $this->optionsResolverMock->expects($this->once())
            ->method('setAllowedTypes')
            ->with($this->equalTo('handler_transformation_failed_fatal'), $this->equalTo('boolean'));

        $this->transformationFailedTypeExtension->configureOptions($this->optionsResolverMock);
    }

    public function testConfigureOptionsTrigger()
    {
        $this->optionsResolverMock->expects($this->once())
            ->method('hasDefault')
            ->with($this->equalTo('handler_invalid_fatal'))
            ->willReturn(false);

        $this->optionsResolverMock->expects($this->once())
            ->method('setDefaults')
            ->with($this->equalTo([
                'handler_transformation_failed_fatal' => false,
                'handler_transformation_failed_data' => null,
            ]));

        $this->optionsResolverMock->expects($this->once())
            ->method('setAllowedTypes')
            ->with($this->equalTo('handler_transformation_failed_fatal'), $this->equalTo('boolean'));

        $this->transformationFailedTypeExtension->configureOptions($this->optionsResolverMock);
    }

    public function testGetExtendedType()
    {
        $this->assertEquals('Symfony\Component\Form\Extension\Core\Type\FormType', $this->transformationFailedTypeExtension->getExtendedType());
    }
}
