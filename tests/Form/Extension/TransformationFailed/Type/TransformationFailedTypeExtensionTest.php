<?php

namespace Symftony\FormHandler\Tests\Form\Extension\Invalid\Type;

use Symftony\FormHandler\Form\Extension\TransformationFailed\Type\TransformationFailedTypeExtension;
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
            ->method('setDefaults')
            ->with($this->equalTo([
                'handler_transformation_failed' => false,
            ]));

        $this->transformationFailedTypeExtension->configureOptions($this->optionsResolverMock);
    }

    public function testGetExtendedType()
    {
        $this->assertEquals('Symfony\Component\Form\Extension\Core\Type\FormType', $this->transformationFailedTypeExtension->getExtendedType());
    }
}
