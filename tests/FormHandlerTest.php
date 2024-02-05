<?php
declare(strict_types=1);

namespace Symftony\FormHandler\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symftony\FormHandler\Exception\InvalidFormException;
use Symftony\FormHandler\Exception\NotSubmittedFormException;
use Symftony\FormHandler\Exception\TransformationFailedFormException;
use Symftony\FormHandler\FormHandler;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormHandlerTest extends TestCase
{
    use ProphecyTrait;

    private FormFactoryInterface|ObjectProphecy $formFactoryMock;

    private FormInterface|ObjectProphecy $formMock;

    private FormConfigInterface|ObjectProphecy $formConfigMock;

    private FormHandler|ObjectProphecy $formHandler;

    public function setUp(): void
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

    public function testHandleRequestThrowNotSubmittedFormException()
    {
        $this->expectException(NotSubmittedFormException::class);
        $this->expectExceptionMessage('Not submitted form.');

        $this->formMock
            ->handleRequest('my_fake_request')
            ->willReturn($this->formMock->reveal())
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
            ->willReturn($this->formMock->reveal())
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

    public function testHandleRequestThrowInvalidFormException()
    {
        $this->expectException(InvalidFormException::class);
        $this->expectExceptionMessage('Invalid form');

        $this->formMock
            ->handleRequest('my_fake_request')
            ->willReturn($this->formMock->reveal())
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
            ->willReturn($this->formMock->reveal())
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

    public function testHandleRequestThrowTransformationFailedFormException()
    {
        $this->expectException(TransformationFailedFormException::class);
        $this->expectExceptionMessage('Transformation form failed.');

        $this->formMock
            ->handleRequest('my_fake_request')
            ->willReturn($this->formMock->reveal())
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
            ->willReturn(new TransformationFailedException())
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
            ->willReturn($this->formMock->reveal())
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
            ->willReturn(new TransformationFailedException())
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
            ->willReturn($this->formMock->reveal())
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
            ->willReturn(null)
            ->shouldBeCalled();

        $this->formMock
            ->getData()
            ->willReturn('my_fake_form_data')
            ->shouldBeCalled();

        $this->assertEquals('my_fake_form_data', $this->formHandler->handleRequest($this->formMock->reveal(), 'my_fake_request'));
    }

    public function testHandleDataThrowNotSubmittedFormException()
    {
        $this->expectException(NotSubmittedFormException::class);
        $this->expectExceptionMessage('Not submitted form.');

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

    public function testHandleDataThrowInvalidFormException()
    {
        $this->expectException(InvalidFormException::class);
        $this->expectExceptionMessage('Invalid form.');

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

        $this->formMock->submit(['my_fake_key' => 'my_fake_request_value'], true)
            ->willReturn($this->formMock->reveal())
            ->shouldBeCalled();

        $this->formMock->isSubmitted()
            ->willReturn(true)
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

        $this->formHandler->handleData($this->formMock->reveal(), ['my_fake_key' => 'my_fake_request_value'], true);
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

        $this->formMock->submit(['my_fake_key' => 'my_fake_request_value'], true)
            ->willReturn($this->formMock->reveal())
            ->shouldBeCalled();

        $this->formMock->isSubmitted()
            ->willReturn(true)
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

        $this->assertEquals('my_fake_handler_invalid_data', $this->formHandler->handleData($this->formMock->reveal(), ['my_fake_key' => 'my_fake_request_value'], true));
    }

    public function testHandleDataThrowTransformationFailedFormException()
    {
        $this->expectException(TransformationFailedFormException::class);
        $this->expectExceptionMessage('Transformation form failed.');

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

        $this->formMock->submit(['my_fake_key' => 'my_fake_request_value'], true)
            ->willReturn($this->formMock->reveal())
            ->shouldBeCalled();

        $this->formMock->isSubmitted()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock->isValid()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock->getTransformationFailure()
            ->willReturn(new TransformationFailedException())
            ->shouldBeCalled();

        $this->formConfigMock->hasOption('handler_transformation_failed')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock->getOption('handler_transformation_failed')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formHandler->handleData($this->formMock->reveal(), ['my_fake_key' => 'my_fake_request_value'], true);
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

        $this->formMock->submit(['my_fake_key' => 'my_fake_request_value'], true)
            ->willReturn($this->formMock->reveal())
            ->shouldBeCalled();

        $this->formMock->isSubmitted()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock->isValid()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock->getTransformationFailure()
            ->willReturn(new TransformationFailedException())
            ->shouldBeCalled();

        $this->formConfigMock->hasOption('handler_transformation_failed')
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formConfigMock->getOption('handler_transformation_failed')
            ->willReturn('my_fake_handler_transformation_failed_data')
            ->shouldBeCalled();

        $this->assertEquals('my_fake_handler_transformation_failed_data', $this->formHandler->handleData($this->formMock->reveal(), ['my_fake_key' => 'my_fake_request_value'], true));
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

        $this->formMock->submit(['my_fake_key' => 'my_fake_request_value'], true)
            ->willReturn($this->formMock->reveal())
            ->shouldBeCalled();

        $this->formMock->isSubmitted()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock->isValid()
            ->willReturn(true)
            ->shouldBeCalled();

        $this->formMock->getTransformationFailure()
            ->willReturn(null)
            ->shouldBeCalled();

        $this->formMock->getData()
            ->willReturn('my_fake_form_data')
            ->shouldBeCalled();

        $this->assertEquals('my_fake_form_data', $this->formHandler->handleData($this->formMock->reveal(), ['my_fake_key' => 'my_fake_request_value'], true));
    }
}
