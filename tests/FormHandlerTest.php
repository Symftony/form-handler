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
        $this->formFactoryMock = $this->getMock(FormFactoryInterface::class);
        $this->formConfigMock = $this->getMock(FormConfigInterface::class);
        $this->formMock = $this->getMock(FormInterface::class);

        $this->formHandler = new FormHandler();
    }

    public function testCreateNamedForm()
    {
        $this->formFactoryMock->expects($this->once())
            ->method('createNamed')
            ->with($this->equalTo('my_fake_name'), $this->equalTo('my_fake_type'), $this->equalTo('my_fake_data'), $this->equalTo(['my_fake_options']));

        $this->formHandler->setFormFactory($this->formFactoryMock);
        $this->formHandler->createForm('my_fake_type', 'my_fake_name', 'my_fake_data', ['my_fake_options']);
    }

    public function testCreateForm()
    {
        $this->formFactoryMock->expects($this->once())
            ->method('create')
            ->with($this->equalTo('my_fake_type'), $this->equalTo('my_fake_data'), $this->equalTo(['my_fake_options']));

        $this->formHandler->setFormFactory($this->formFactoryMock);
        $this->formHandler->createForm('my_fake_type', null, 'my_fake_data', ['my_fake_options']);
    }

    /**
     * @expectedException \Symftony\FormHandler\Exception\NotSubmittedFormException
     * @expectedExceptionMessage Not submitted form.
     */
    public function testHandleRequestThrowNotSubmittedFormException()
    {
        $this->formMock->expects($this->once())
            ->method('handleRequest')
            ->with('my_fake_request');

        $this->formMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->formConfigMock);

        $this->formMock->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(false);

        $this->formConfigMock->expects($this->once())
            ->method('getOption')
            ->with($this->equalTo('handler_not_submitted'), $this->equalTo(false))
            ->willReturn(true);

        $this->formHandler->handleRequest($this->formMock, 'my_fake_request');
    }

    public function testHandleRequestNotSubmitted()
    {
        $this->formMock->expects($this->once())
            ->method('handleRequest')
            ->with('my_fake_request');

        $this->formMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->formConfigMock);

        $this->formMock->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(false);

        $this->formConfigMock->expects($this->once())
            ->method('getOption')
            ->with($this->equalTo('handler_not_submitted'), $this->equalTo(false))
            ->willReturn('my_fake_handler_not_submitted_data');

        $this->assertEquals('my_fake_handler_not_submitted_data', $this->formHandler->handleRequest($this->formMock, 'my_fake_request'));
    }

    /**
     * @expectedException \Symftony\FormHandler\Exception\InvalidFormException
     * @expectedExceptionMessage Invalid form.
     */
    public function testHandleRequestThrowInvalidFormException()
    {
        $this->formMock->expects($this->once())
            ->method('handleRequest')
            ->with('my_fake_request');

        $this->formMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->formConfigMock);

        $this->formMock->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $this->formMock->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->formConfigMock->expects($this->once())
            ->method('getOption')
            ->with($this->equalTo('handler_invalid'), $this->equalTo(false))
            ->willReturn(true);

        $this->formHandler->handleRequest($this->formMock, 'my_fake_request');
    }

    public function testHandleRequestInvalid()
    {
        $this->formMock->expects($this->once())
            ->method('handleRequest')
            ->with('my_fake_request');

        $this->formMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->formConfigMock);

        $this->formMock->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $this->formMock->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->formConfigMock->expects($this->once())
            ->method('getOption')
            ->with($this->equalTo('handler_invalid'), $this->equalTo(false))
            ->willReturn('my_fake_handler_invalid_data');

        $this->formMock->expects($this->once())
            ->method('getTransformationFailure')
            ->willReturn(false);

        $this->assertEquals('my_fake_handler_invalid_data', $this->formHandler->handleRequest($this->formMock, 'my_fake_request'));
    }

    /**
     * @expectedException \Symftony\FormHandler\Exception\TransformationFailedFormException
     * @expectedExceptionMessage Transformation form failed.
     */
    public function testHandleRequestThrowTransformationFailedFormException()
    {
        $this->formMock->expects($this->once())
            ->method('handleRequest')
            ->with('my_fake_request');

        $this->formMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->formConfigMock);

        $this->formMock->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $this->formMock->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $this->formConfigMock->expects($this->exactly(2))
            ->method('getOption')
            ->withConsecutive(
                [$this->equalTo('handler_invalid'), $this->equalTo(false)],
                [$this->equalTo('handler_transformation_failed'), $this->equalTo(false)]
            )
            ->will($this->onConsecutiveCalls(
                'useless value in this case',
                true
            ));

        $this->formMock->expects($this->once())
            ->method('getTransformationFailure')
            ->willReturn(true);

        $this->formHandler->handleRequest($this->formMock, 'my_fake_request');
    }

    public function testHandleRequestTransformationFailed()
    {
        $this->formMock->expects($this->once())
            ->method('handleRequest')
            ->with('my_fake_request');

        $this->formMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->formConfigMock);

        $this->formMock->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $this->formConfigMock->expects($this->exactly(2))
            ->method('getOption')
            ->withConsecutive(
                [$this->equalTo('handler_invalid'), $this->equalTo(false)],
                [$this->equalTo('handler_transformation_failed'), $this->equalTo(false)]
            )
            ->will($this->onConsecutiveCalls(
                'useless value in this case',
                'my_fake_handler_transformation_failed_data'
            ));

        $this->formMock->expects($this->once())
            ->method('getTransformationFailure')
            ->willReturn(true);

        $this->assertEquals('my_fake_handler_transformation_failed_data', $this->formHandler->handleRequest($this->formMock, 'my_fake_request'));
    }

    public function testHandleRequest()
    {
        $this->formMock->expects($this->once())
            ->method('handleRequest')
            ->with('my_fake_request');

        $this->formMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->formConfigMock);

        $this->formMock->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $this->formMock->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $this->formConfigMock->expects($this->once())
            ->method('getOption')
            ->with($this->equalTo('handler_invalid'), $this->equalTo(false))
            ->willReturn('useless value in this case');

        $this->formMock->expects($this->once())
            ->method('getTransformationFailure')
            ->willReturn(false);

        $this->formMock->expects($this->once())
            ->method('getData')
            ->willReturn('my_fake_form_data');

        $this->assertEquals('my_fake_form_data', $this->formHandler->handleRequest($this->formMock, 'my_fake_request'));
    }

    /**
     * @expectedException \Symftony\FormHandler\Exception\NotSubmittedFormException
     * @expectedExceptionMessage Not submitted form.
     */
    public function testHandleDataThrowNotSubmittedFormException()
    {
        $this->formMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->formConfigMock);

        $this->formMock->expects($this->once())
            ->method('getName')
            ->willReturn('');

        $this->formMock->expects($this->once())
            ->method('all')
            ->willReturn([]);

        $this->formConfigMock->expects($this->once())
            ->method('getOption')
            ->with($this->equalTo('handler_not_submitted'), $this->equalTo(false))
            ->willReturn(true);

        $this->formHandler->handleData($this->formMock, ['my_fake_request']);
    }

    public function testHandleDataNotSubmitted()
    {
        $this->formMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->formConfigMock);

        $this->formMock->expects($this->once())
            ->method('getName')
            ->willReturn('');

        $this->formMock->expects($this->once())
            ->method('all')
            ->willReturn([]);

        $this->formConfigMock->expects($this->once())
            ->method('getOption')
            ->with($this->equalTo('handler_not_submitted'), $this->equalTo(false))
            ->willReturn('my_fake_handler_not_submitted_data');

        $this->assertEquals('my_fake_handler_not_submitted_data', $this->formHandler->handleData($this->formMock, ['my_fake_request']));
    }

    /**
     * @expectedException \Symftony\FormHandler\Exception\InvalidFormException
     * @expectedExceptionMessage Invalid form.
     */
    public function testHandleDataThrowInvalidFormException()
    {
        $this->formMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->formConfigMock);

        $this->formMock->expects($this->once())
            ->method('getName')
            ->willReturn('');

        $this->formMock->expects($this->once())
            ->method('all')
            ->willReturn(['my_fake_key' => 'my_fake_form_value']);

        $this->formMock->expects($this->once())
            ->method('submit')
            ->with(['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing');

        $this->formMock->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->formConfigMock->expects($this->once())
            ->method('getOption')
            ->with($this->equalTo('handler_invalid'), $this->equalTo(false))
            ->willReturn(true);

        $this->formHandler->handleData($this->formMock, ['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing');
    }

    public function testHandleDataInvalid()
    {
        $this->formMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->formConfigMock);

        $this->formMock->expects($this->once())
            ->method('getName')
            ->willReturn('');

        $this->formMock->expects($this->once())
            ->method('all')
            ->willReturn(['my_fake_key' => 'my_fake_form_value']);

        $this->formMock->expects($this->once())
            ->method('submit')
            ->with(['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing');

        $this->formMock->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->formConfigMock->expects($this->once())
            ->method('getOption')
            ->with($this->equalTo('handler_invalid'), $this->equalTo(false))
            ->willReturn('my_fake_handler_invalid_data');

        $this->formMock->expects($this->once())
            ->method('getTransformationFailure')
            ->willReturn(false);

        $this->assertEquals('my_fake_handler_invalid_data', $this->formHandler->handleData($this->formMock, ['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing'));
    }

    /**
     * @expectedException \Symftony\FormHandler\Exception\TransformationFailedFormException
     * @expectedExceptionMessage Transformation form failed.
     */
    public function testHandleDataThrowTransformationFailedFormException()
    {
        $this->formMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->formConfigMock);

        $this->formMock->expects($this->once())
            ->method('getName')
            ->willReturn('');

        $this->formMock->expects($this->once())
            ->method('all')
            ->willReturn(['my_fake_key' => 'my_fake_form_value']);

        $this->formMock->expects($this->once())
            ->method('submit')
            ->with(['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing');

        $this->formMock->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $this->formConfigMock->expects($this->exactly(2))
            ->method('getOption')
            ->withConsecutive(
                [$this->equalTo('handler_invalid'), $this->equalTo(false)],
                [$this->equalTo('handler_transformation_failed'), $this->equalTo(false)]
            )
            ->will($this->onConsecutiveCalls(
                'my_fake_handler_invalid_data',
                true
            ));

        $this->formMock->expects($this->once())
            ->method('getTransformationFailure')
            ->willReturn(true);

        $this->formHandler->handleData($this->formMock, ['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing');
    }

    public function testHandleDataTransformationFailed()
    {
        $this->formMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->formConfigMock);

        $this->formMock->expects($this->once())
            ->method('getName')
            ->willReturn('');

        $this->formMock->expects($this->once())
            ->method('all')
            ->willReturn(['my_fake_key' => 'my_fake_form_value']);

        $this->formMock->expects($this->once())
            ->method('submit')
            ->with(['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing');

        $this->formMock->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $this->formConfigMock->expects($this->exactly(2))
            ->method('getOption')
            ->withConsecutive(
                [$this->equalTo('handler_invalid'), $this->equalTo(false)],
                [$this->equalTo('handler_transformation_failed'), $this->equalTo(false)]
            )
            ->will($this->onConsecutiveCalls(
                'my_fake_handler_invalid_data',
                'my_fake_handler_transformation_failed_data'
            ));

        $this->formMock->expects($this->once())
            ->method('getTransformationFailure')
            ->willReturn(true);

        $this->assertEquals('my_fake_handler_transformation_failed_data', $this->formHandler->handleData($this->formMock, ['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing'));
    }

    public function testHandleData()
    {
        $this->formMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->formConfigMock);

        $this->formMock->expects($this->once())
            ->method('getName')
            ->willReturn('');

        $this->formMock->expects($this->once())
            ->method('all')
            ->willReturn(['my_fake_key' => 'my_fake_form_value']);

        $this->formMock->expects($this->once())
            ->method('submit')
            ->with(['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing');

        $this->formMock->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $this->formConfigMock->expects($this->once())
            ->method('getOption')
            ->with($this->equalTo('handler_invalid'), $this->equalTo(false))
            ->willReturn('useless value in this case');

        $this->formMock->expects($this->once())
            ->method('getTransformationFailure')
            ->willReturn(false);

        $this->formMock->expects($this->once())
            ->method('getData')
            ->willReturn('my_fake_form_data');

        $this->assertEquals('my_fake_form_data', $this->formHandler->handleData($this->formMock, ['my_fake_key' => 'my_fake_request_value'], 'my_fake_clear_missing'));
    }
}
