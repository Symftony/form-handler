<?php

namespace Symftony\FormHandler\Tests\Form\Extension\Invalid\Type;

use Symftony\FormHandler\Form\Extension\Invalid\Type\InvalidTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvalidTypeExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OptionsResolver
     */
    private $optionsResolverMock;

    /**
     * @var InvalidTypeExtension
     */
    private $invalidTypeExtension;

    public function setUp()
    {
        $this->optionsResolverMock = $this->getMock(OptionsResolver::class);

        $this->invalidTypeExtension = new InvalidTypeExtension();
    }

    public function testConfigureOptions()
    {
        $this->optionsResolverMock->expects($this->once())
            ->method('setDefaults')
            ->with($this->equalTo([
                'handler_invalid' => false,
            ]));

        $this->invalidTypeExtension->configureOptions($this->optionsResolverMock);
    }

    public function testGetExtendedType()
    {
        $this->assertEquals('Symfony\Component\Form\Extension\Core\Type\FormType', $this->invalidTypeExtension->getExtendedType());
    }
}
