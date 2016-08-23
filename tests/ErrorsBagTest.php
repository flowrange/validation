<?php

namespace Flowrange\Validation\Tests;

use Flowrange\Validation\ErrorsBag;

/**
 * ErrorsBag test case
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class ErrorsBagTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Tested error bag
     * @var ErrorsBag
     */
    private $errorsBag;


    public function setUp()
    {
        parent::setUp();

        $this->errorsBag = new ErrorsBag(
            [
                'some-field' => ['some-error']
            ],
            [
                'some-field' => ['some-error']
            ]);

        $this->errorsBag->addFieldError('some-field', 'an-error');
        $this->errorsBag->addFieldError('other-field', 'other-error');

        $this->errorsBag->addGlobalError('some-field', 'an-error');
        $this->errorsBag->addGlobalError('other-field', 'other-error');
    }


    public function testConstructor()
    {
        $this->assertInstanceOf(
            ErrorsBag::class,
            $this->errorsBag);
    }


    public function testHasErrorsReturnsFalseIfNoErrors()
    {
        $errorsBag = new ErrorsBag();

        $this->assertFalse($errorsBag->hasErrors());
    }


    public function testHasErrorsReturnsTrueIfGlobalErrors()
    {
        $errorsBag = new ErrorsBag(['some-field' => ['some-error']]);

        $this->assertTrue($errorsBag->hasErrors());
    }


    public function testHasErrorsReturnsTrueIfFieldsErrors()
    {
        $errorsBag = new ErrorsBag([], ['some-field' => ['some-error']]);

        $this->assertTrue($errorsBag->hasErrors());
    }


    public function testHasFieldErrorsReturnsFalseIfNoErrors()
    {
        $errorsBag = new ErrorsBag([]);

        $this->assertFalse($errorsBag->hasFieldErrors('some-field'));
    }


    public function testHasFieldErrorsReturnsTrueIfFieldsHasErrors()
    {
        $errorsBag = new ErrorsBag([], ['some-field' => ['some-error']]);

        $this->assertTrue($errorsBag->hasFieldErrors('some-field'));
    }


    public function testHasFieldErrorsReturnsFalseIfFieldSetButNoErrors()
    {
        $errorsBag = new ErrorsBag([], ['some-field' => []]);

        $this->assertFalse($errorsBag->hasFieldErrors('some-field'));
    }


    public function testAddGlobalErrorAddsGlobalError()
    {
        $errors = $this->errorsBag->getGlobalErrors();

        $this->assertEquals(
            [
                'some-field' => [
                    'some-error', 'an-error'
                ],
                'other-field' => ['other-error']
            ],
            $errors);
    }


    public function testAddGlobalErrorsDoesntAddErrorIfAlreadyHere()
    {
        $this->errorsBag->addGlobalError('some-field', 'some-error');

        $errors = $this->errorsBag->getGlobalErrors();

        $this->assertEquals(
            [
                'some-field' => [
                    'some-error', 'an-error'
                ],
                'other-field' => ['other-error']
            ],
            $errors);
    }


    public function testAddFieldErrorAddsFieldErrors()
    {
        $errors = $this->errorsBag->getFieldsErrors();

        $this->assertEquals(
            [
                'some-field' => [
                    'some-error', 'an-error'
                ],
                'other-field' => ['other-error']
            ],
            $errors);
    }


    public function testGetFieldErrorsReturnsErrorsForAField()
    {
        $errors = $this->errorsBag->getFieldErrors('some-field');

        $this->assertEquals(
            [
                'some-error', 'an-error'
            ],
            $errors);
    }


    public function testGetFieldErrorsReturnsEmptyArrayIfNoErrors()
    {
        $errors = $this->errorsBag->getFieldErrors('not-exists');

        $this->assertEquals(
            [],
            $errors);
    }

}
