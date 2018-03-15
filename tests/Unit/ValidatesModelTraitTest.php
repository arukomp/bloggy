<?php

namespace Arukomp\Bloggy\Tests\Unit;

use Arukomp\Bloggy\Tests\Stubs\ValidationModelStub;
use Arukomp\Bloggy\Tests\Stubs\ValidationModelWithValidationMessagesStub;
use Arukomp\Bloggy\Tests\TestCase;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;

class ValidatesModelTraitTest extends TestCase
{
    /** @test */
    public function returnsTrueIfDataIsValid()
    {
        $model = new ValidationModelStub([
            'first'  => 'hello',
            'second' => 1,
        ]);

        $this->assertTrue($model->isValid());
    }

    /** @test */
    public function returnsFalseIfDataIsNotValid()
    {
        $model = new ValidationModelStub([
            'first'  => 1,
            'second' => 'hello',
        ]);

        $this->assertFalse($model->isValid());
    }

    /** @test */
    public function itCanRetrieveTheFullErrorsBag()
    {
        $model = new ValidationModelWithValidationMessagesStub([
            'first'  => [],
            'second' => 101,
        ]);

        $this->assertInstanceOf(MessageBag::class, $model->errors());
        $this->assertEquals('First is not a string!', $model->errors()->first('first'));
    }

    /** @test */
    public function itCanGetErrorsForFailedValidations()
    {
        $model = new ValidationModelStub([
            'first'  => 1,
            'second' => 'hello',
        ]);

        $expectedValidationErrors = [
            'first'  => ['The first must be a string.'],
            'second' => ['The second must be an integer.'],
        ];

        $this->assertEquals($expectedValidationErrors, $model->errors()->toArray());
    }

    /** @test */
    public function itCanHaveCustomValidationMessages()
    {
        $model = new ValidationModelWithValidationMessagesStub([
            'first'  => [],
            'second' => 101,
        ]);

        $expectedValidationErrors = [
            'first'  => ['First is not a string!'],
            'second' => ['Second number is too large.'],
        ];

        $this->assertEquals($expectedValidationErrors, $model->errors()->toArray());
    }

    /** @test */
    public function savingAModelWithInvalidDataThrowsAnException()
    {
        $model = new ValidationModelWithValidationMessagesStub([
            'first'  => [],
            'second' => 101,
        ]);

        $this->expectException(ValidationException::class);

        $model->save();
    }

    /** @test */
    public function validateMethodReturnsTheModelForChaining()
    {
        $model = new ValidationModelStub([
            'first'  => 'asdf',
            'second' => 100,
        ]);

        $this->assertEquals($model, $model->validate());
    }
}
