<?php

namespace Symftony\FormHandler\Tests\Form\Extension\Invalid\Type;

use Symftony\FormHandler\Form\Extension\NotSubmitted\Type\NotSubmittedTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotSubmittedTypeExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OptionsResolver
     */
    private $optionsResolverMock;

    /**
     * @var NotSubmittedTypeExtension
     */
    private $notSubmittedTypeExtension;

    public function setUp()
    {
        $this->optionsResolverMock = $this->getMock(OptionsResolver::class);

        $this->notSubmittedTypeExtension = new NotSubmittedTypeExtension();
    }

    public function testConfigureOptions()
    {
        $this->optionsResolverMock->expects($this->once())
            ->method('setDefaults')
            ->with($this->equalTo([
                'handler_not_submitted_fatal' => false,
                'handler_not_submitted_data' => null,
            ]));

        $this->optionsResolverMock->expects($this->once())
            ->method('setAllowedTypes')
            ->with($this->equalTo('handler_not_submitted_fatal'), $this->equalTo('boolean'));

        $this->notSubmittedTypeExtension->configureOptions($this->optionsResolverMock);
    }

    public function testGetExtendedType()
    {
        $this->assertEquals('Symfony\Component\Form\Extension\Core\Type\FormType', $this->notSubmittedTypeExtension->getExtendedType());
    }
}
