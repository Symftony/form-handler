<?php

namespace Symftony\FormHandler\Tests;

use Symftony\FormHandler\FormHandler;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactoryMock;

    /**
     * @var FormInterface
     */
    private $formMock;

    /**
     * @var FormConfigInterface
     */
    private $formConfigMock;

    /**
     * @var FormHandler
     */
    private $formHandler;

    public function setUp()
    {
        $this->formFactoryMock = $this->prophesize(FormFactoryInterface::class);
        $this->formConfigMock = $this->prophesize(FormConfigInterface::class);
        $this->formMock = $this->prophesize(FormInterface::class);

        $this->formHandler = new FormHandler();
    }

    public function testCreateNamedForm()
    {
        $this->formFactoryMock
            ->createNamed('my_fake_name', 'my_fake_type', 'my_fake_data', ['my_fake_options'])
            ->shouldBeCalled();

        $this->formHandler->setFormFactory($this->formFactoryMock->reveal());
        $this->formHandler->createForm('my_fake_type', 'my_fake_name', 'my_fake_data', ['my_fake_options']);
    }

    public function testCreateForm()
    {
        $this->formFactoryMock
            ->create('my_fake_type', 'my_fake_data', ['my_fake_options'])
            ->shouldBeCalled();

        $this->formHandler->setFormFactory($this->formFactoryMock->reveal());
        $this->formHandler->createForm('my_fake_type', null, 'my_fake_data', ['my_fake_options']);
    }

    /**
     * @expectedException \Symftony\FormHandler\Exception\NotSubmittedFormException
     * @expectedExceptionMessage Not submitted form.
     */
    public function testHandleRequestThrowNotSubmittedFormException()
    {
        $this->formMock
            ->handleRequest('my_fake_request')
            ->shouldBeCalled();

        $this->formMock
            ->getConfig()
            ->willReturn($this->formConfigMock->reveal())
            ->shouldBeCalled();

        $this->formMock
            ->isSubmitted()
            ->willReturn(false)
            ->shouldBeCalled();

        $this->formConfigMock
            ->hasOption('handler_not_submitted')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock
            ->getOption('handler_not_submitted')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formHandler->handleRequest($this->formMock->reveal(), 'my_fake_request');
    }

    public function testHandleRequestNotSubmitted()
    {
        $this->formMock
            ->handleRequest('my_fake_request')
            ->shouldBeCalled();

        $this->formMock
            ->getConfig()
            ->willReturn($this->formConfigMock->reveal())
            ->shouldBeCalled();

        $this->formMock
            ->isSubmitted()
            ->willReturn(false)
            ->shouldBeCalled();

        $this->formConfigMock
            ->hasOption('handler_not_submitted')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock
            ->getOption('handler_not_submitted')
            ->willReturn('my_fake_handler_not_submitted_data')
            ->shouldBeCalled();

        $this->assertEquals('my_fake_handler_not_submitted_data', $this->formHandler->handleRequest($this->formMock->reveal(), 'my_fake_request'));
    }

    /**
     * @expectedException \Symftony\FormHandler\Exception\InvalidFormException
     * @expectedExceptionMessage Invalid form.
     */
    public function testHandleRequestThrowInvalidFormException()
    {
        $this->formMock
            ->handleRequest('my_fake_request')
            ->shouldBeCalled();

        $this->formMock
            ->getConfig()
            ->willReturn($this->formConfigMock->reveal())
            ->shouldBeCalled();

        $this->formMock
            ->isSubmitted()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock
            ->isValid()
            ->willReturn(false)
            ->shouldBeCalled();

        $this->formConfigMock
            ->hasOption('handler_invalid')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock
            ->getOption('handler_invalid')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formHandler->handleRequest($this->formMock->reveal(), 'my_fake_request');
    }

    public function testHandleRequestInvalid()
    {
        $this->formMock
            ->handleRequest('my_fake_request')
            ->shouldBeCalled();

        $this->formMock
            ->getConfig()
            ->willReturn($this->formConfigMock->reveal())
            ->shouldBeCalled();

        $this->formMock
            ->isSubmitted()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock
            ->isValid()
            ->willReturn(false)
            ->shouldBeCalled();

        $this->formConfigMock
            ->hasOption('handler_invalid')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock
            ->getOption('handler_invalid')
            ->willReturn('my_fake_handler_invalid_data')
            ->shouldBeCalled();

        $this->assertEquals('my_fake_handler_invalid_data', $this->formHandler->handleRequest($this->formMock->reveal(), 'my_fake_request'));
    }

    /**
     * @expectedException \Symftony\FormHandler\Exception\TransformationFailedFormException
     * @expectedExceptionMessage Transformation form failed.
     */
    public function testHandleRequestThrowTransformationFailedFormException()
    {
        $this->formMock
            ->handleRequest('my_fake_request')
            ->shouldBeCalled();

        $this->formMock
            ->getConfig()
            ->willReturn($this->formConfigMock->reveal())
            ->shouldBeCalled();

        $this->formMock
            ->isSubmitted()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock
            ->isValid()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock
            ->getTransformationFailure()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock
            ->hasOption('handler_transformation_failed')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock
            ->getOption('handler_transformation_failed')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formHandler->handleRequest($this->formMock->reveal(), 'my_fake_request');
    }

    public function testHandleRequestTransformationFailed()
    {
        $this->formMock
            ->handleRequest('my_fake_request')
            ->shouldBeCalled()
            ->shouldBeCalled();

        $this->formMock
            ->getConfig()
            ->willReturn($this->formConfigMock->reveal())
            ->shouldBeCalled();

        $this->formMock
            ->isSubmitted()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock
            ->isValid()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock
            ->getTransformationFailure()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock
            ->hasOption('handler_transformation_failed')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock
            ->getOption('handler_transformation_failed')
            ->willReturn('my_fake_handler_transformation_failed_data')
            ->shouldBeCalled();

        $this->assertEquals('my_fake_handler_transformation_failed_data', $this->formHandler->handleRequest($this->formMock->reveal(), 'my_fake_request'));
    }

    public function testHandleRequest()
    {
        $this->formMock
            ->handleRequest('my_fake_request')
            ->shouldBeCalled();

        $this->formMock
            ->getConfig()
            ->willReturn($this->formConfigMock->reveal())
            ->shouldBeCalled();

        $this->formMock
            ->isSubmitted()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock
            ->isValid()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock
            ->getTransformationFailure()
            ->willReturn(false)
            ->shouldBeCalled();

        $this->formMock
            ->getData()
            ->willReturn('my_fake_form_data')
            ->shouldBeCalled();

        $this->assertEquals('my_fake_form_data', $this->formHandler->handleRequest($this->formMock->reveal(), 'my_fake_request'));
    }

    /**
     * @expectedException \Symftony\FormHandler\Exception\NotSubmittedFormException
     * @expectedExceptionMessage Not submitted form.
     */
    public function testHandleDataThrowNotSubmittedFormException()
    {
        $this->formMock->getConfig()
            ->willReturn($this->formConfigMock)
            ->shouldBeCalled();

        $this->formMock->getName()
            ->willReturn('')
            ->shouldBeCalled();

        $this->formMock->all()
            ->willReturn([])
            ->shouldBeCalled();

        $this->formConfigMock->hasOption('handler_not_submitted')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock->getOption('handler_not_submitted')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formHandler->handleData($this->formMock->reveal(), ['my_fake_request']);
    }

    public function testHandleDataNotSubmitted()
    {
        $this->formMock->getConfig()
            ->willReturn($this->formConfigMock)
            ->shouldBeCalled();

        $this->formMock->getName()
            ->willReturn('')
            ->shouldBeCalled();

        $this->formMock->all()
            ->willReturn([])
            ->shouldBeCalled();

        $this->formConfigMock->hasOption('handler_not_submitted')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock->getOption('handler_not_submitted')
            ->willReturn('my_fake_handler_not_submitted_data')
            ->shouldBeCalled();

        $this->assertEquals('my_fake_handler_not_submitted_data', $this->formHandler->handleData($this->formMock->reveal(), ['my_fake_request']));
    }

    /**
     * @expectedException \Symftony\FormHandler\Exception\InvalidFormException
     * @expectedExceptionMessage Invalid form.
     */
    public function testHandleDataThrowInvalidFormException()
    {
        $this->formMock->getConfig()
            ->willReturn($this->formConfigMock)
            ->shouldBeCalled();

        $this->formMock->getName()
            ->willReturn('')
            ->shouldBeCalled();

        $this->formMock->all()
            ->willReturn([])
            ->shouldBeCalled();

        $this->formConfigMock->hasOption('handler_not_submitted')
            ->willReturn(false)
            ->shouldBeCalled();

        $this->formMock->submit(['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing')
            ->shouldBeCalled();

        $this->formMock->isValid()
            ->willReturn(false)
            ->shouldBeCalled();

        $this->formConfigMock->hasOption('handler_invalid')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock->getOption('handler_invalid')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formHandler->handleData($this->formMock->reveal(), ['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing');
    }

    public function testHandleDataInvalid()
    {
        $this->formMock->getConfig()
            ->willReturn($this->formConfigMock)
            ->shouldBeCalled();

        $this->formMock->getName()
            ->willReturn('')
            ->shouldBeCalled();

        $this->formMock->all()
            ->willReturn([])
            ->shouldBeCalled();

        $this->formConfigMock->hasOption('handler_not_submitted')
            ->willReturn(false)
            ->shouldBeCalled();

        $this->formMock->submit(['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing')
            ->shouldBeCalled();

        $this->formMock->isValid()
            ->willReturn(false)
            ->shouldBeCalled();

        $this->formConfigMock->hasOption('handler_invalid')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock->getOption('handler_invalid')
            ->willReturn('my_fake_handler_invalid_data')
            ->shouldBeCalled();

        $this->assertEquals('my_fake_handler_invalid_data', $this->formHandler->handleData($this->formMock->reveal(), ['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing'));
    }

    /**
     * @expectedException \Symftony\FormHandler\Exception\TransformationFailedFormException
     * @expectedExceptionMessage Transformation form failed.
     */
    public function testHandleDataThrowTransformationFailedFormException()
    {
        $this->formMock->getConfig()
            ->willReturn($this->formConfigMock)
            ->shouldBeCalled();

        $this->formMock->getName()
            ->willReturn('')
            ->shouldBeCalled();

        $this->formMock->all()
            ->willReturn([])
            ->shouldBeCalled();

        $this->formConfigMock->hasOption('handler_not_submitted')
            ->willReturn(false)
            ->shouldBeCalled();

        $this->formMock->submit(['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing')
            ->shouldBeCalled();

        $this->formMock->isValid()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock->getTransformationFailure()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock->hasOption('handler_transformation_failed')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock->getOption('handler_transformation_failed')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formHandler->handleData($this->formMock->reveal(), ['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing');
    }

    public function testHandleDataTransformationFailed()
    {
        $this->formMock->getConfig()
            ->willReturn($this->formConfigMock)
            ->shouldBeCalled();

        $this->formMock->getName()
            ->willReturn('')
            ->shouldBeCalled();

        $this->formMock->all()
            ->willReturn([])
            ->shouldBeCalled();

        $this->formConfigMock->hasOption('handler_not_submitted')
            ->willReturn(false)
            ->shouldBeCalled();

        $this->formMock->submit(['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing')
            ->shouldBeCalled();

        $this->formMock->isValid()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock->getTransformationFailure()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock->hasOption('handler_transformation_failed')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock->getOption('handler_transformation_failed')
            ->willReturn('my_fake_handler_transformation_failed_data')
            ->shouldBeCalled();

        $this->assertEquals('my_fake_handler_transformation_failed_data', $this->formHandler->handleData($this->formMock->reveal(), ['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing'));
    }

    public function testHandleData()
    {
        $this->formMock->getConfig()
            ->willReturn($this->formConfigMock)
            ->shouldBeCalled();

        $this->formMock->getName()
            ->willReturn('')
            ->shouldBeCalled();

        $this->formMock->all()
            ->willReturn([])
            ->shouldBeCalled();

        $this->formConfigMock->hasOption('handler_not_submitted')
            ->willReturn(false)
            ->shouldBeCalled();

        $this->formMock->submit(['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing')
            ->shouldBeCalled();

        $this->formMock->isValid()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock->getTransformationFailure()
            ->willReturn(false)
            ->shouldBeCalled();

        $this->formMock->getData()
            ->willReturn('my_fake_form_data')
            ->shouldBeCalled();

        $this->assertEquals('my_fake_form_data', $this->formHandler->handleData($this->formMock->reveal(), ['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing'));
    }
}
