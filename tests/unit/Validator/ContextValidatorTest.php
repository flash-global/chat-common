<?php

namespace Tests\Fei\Service\Chat\Validator;

use Fei\Entity\Validator\Exception;
use Fei\Service\Chat\Entity\Room;
use Fei\Service\Chat\Entity\RoomContext;
use Fei\Service\Chat\Validator\ContextValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class ContextValidatorTest
 *
 * @package Tests\Fei\Service\Chat\Validator
 */
class ContextValidatorTest extends TestCase
{
    /**
     * @dataProvider dataForTestValidateKey
     *
     * @param $key
     * @param $validation
     * @param $error
     */
    public function testValidateKey($key, $validation, $error)
    {
        $validator = new ContextValidator();

        $result = $validator->validateKey($key);

        $this->assertEquals($validation, $result);

        if (!$result) {
            $this->assertEquals($error, $validator->getErrors()['key'][0]);
        }
    }

    public function dataForTestValidateKey()
    {
        return [
            [null, false, 'The key cannot be empty'],
            ['', false, 'The key cannot be empty'],
            ['test', true, null],
            [str_repeat('☃', 256), false, 'The key length has to be less or equal to 255'],
            [str_repeat('☃', 255), true, null]
        ];
    }

    /**
     * @dataProvider dataForTestValidateValue
     *
     * @param $value
     * @param $validation
     * @param $error
     */
    public function testValidateValue($value, $validation, $error)
    {
        $validator = new ContextValidator();

        $result = $validator->validateValue($value);

        $this->assertEquals($validation, $result);

        if (!$result) {
            $this->assertEquals($error, $validator->getErrors()['value'][0]);
        }
    }

    public function dataForTestValidateValue()
    {
        return [
            [null, false, 'The value cannot be empty'],
            ['', false, 'The value cannot be empty'],
            ['test', true, null],
            [str_repeat('☃', 256), true, null],
            [str_repeat('☃', 255), true, null]
        ];
    }

    public function testValidateContextIsEmpty()
    {
        $validator = new ContextValidator();

        $this->assertFalse($validator->validate(new RoomContext()));
    }

    public function testValidateThatEntityIsNotAContext()
    {
        $validator = new ContextValidator();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The Entity to validate must be an instance of \Fei\Service\Chat\Entity\Context');

        $this->assertFalse($validator->validate(new Room()));
    }
}
