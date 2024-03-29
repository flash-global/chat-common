<?php

namespace Tests\Fei\Service\Chat\Validator;

use Codeception\Test\Unit;
use Fei\Entity\Validator\Exception;
use Fei\Service\Chat\Entity\Message;
use Fei\Service\Chat\Entity\Room;
use Fei\Service\Chat\Validator\MessageValidator;

/**
 * Class MessageValidatorTest
 *
 * @package Tests\Fei\Service\Chat\Validator
 */
class MessageValidatorTest extends Unit
{
    public function testValidate()
    {
        $validator = new MessageValidator();

        $room = (new Message())
            ->setBody('body')
            ->setUsername('user')
            ->setRoom(new Room);

        $this->assertTrue($validator->validate($room));
        $this->assertEmpty($validator->getErrors());
    }

    public function testValidateNoMessageEntity()
    {
        $validator = new MessageValidator();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'The Entity to validate must be an instance of ' . Message::class
        );

        $validator->validate(new Room());
    }

    public function testValidateBody()
    {
        $validator = new MessageValidator();

        $this->assertFalse($validator->validateBody(''));
        $this->assertEquals('Chat message body cannot be empty', $validator->getErrors()['body'][0]);

        $validator = new MessageValidator();

        $this->assertFalse($validator->validateBody(array('toto')));
        $this->assertEquals('The chat message body  must be a string', $validator->getErrors()['body'][0]);

        $validator = new MessageValidator();

        $this->assertTrue($validator->validateBody('a body'));
        $this->assertEmpty($validator->getErrors());
    }

    public function testValidateCreateAt()
    {
        $validator = new MessageValidator();

        $this->assertFalse($validator->validateCreatedAt(''));
        $this->assertEquals('Creation date and time cannot be empty', $validator->getErrors()['createdAt'][0]);

        $validator = new MessageValidator();

        $this->assertFalse($validator->validateCreatedAt('test'));
        $this->assertEquals(
            'The creation date has to be and instance of \DateTime',
            $validator->getErrors()['createdAt'][0]
        );

        $validator = new MessageValidator();

        $this->assertTrue($validator->validateCreatedAt(new \DateTime()));
        $this->assertEmpty($validator->getErrors());
    }

    public function testValidateUsername()
    {
        $validator = new MessageValidator();

        $this->assertFalse($validator->validateUsername(''));
        $this->assertEquals('The user cannot be empty', $validator->getErrors()['user'][0]);

        $validator = new MessageValidator();

        $this->assertFalse($validator->validateUsername(array('toto')));
        $this->assertEquals('The user must be a string', $validator->getErrors()['user'][0]);

        $validator = new MessageValidator();

        $this->assertFalse($validator->validateUsername(str_repeat('☃', 256)));
        $this->assertEquals('The user length has to be less or equal to 255', $validator->getErrors()['user'][0]);

        $validator = new MessageValidator();

        $this->assertTrue($validator->validateUsername('toto'));
        $this->assertEmpty($validator->getErrors());
    }

    public function testValidateDisplayUsername()
    {
        $validator = new MessageValidator();

        $this->assertTrue($validator->validateDisplayUsername(''));
        $this->assertTrue($validator->validateDisplayUsername(null));

        $validator = new MessageValidator();

        $this->assertFalse($validator->validateDisplayUsername(array('toto')));
        $this->assertEquals('The display username must be a string', $validator->getErrors()['user'][0]);

        $validator = new MessageValidator();

        $this->assertFalse($validator->validateDisplayUsername(str_repeat('☃', 256)));
        $this->assertEquals('The display username length has to be less or equal to 255', $validator->getErrors()['user'][0]);

        $validator = new MessageValidator();

        $this->assertTrue($validator->validateDisplayUsername('toto'));
        $this->assertEmpty($validator->getErrors());
    }

    public function testValidateRoom()
    {
        $validator = new MessageValidator();

        $this->assertFalse($validator->validateRoom(null));
        $this->assertEquals('Message must be attached to a room', $validator->getErrors()['room'][0]);

        $validator = new MessageValidator();

        $this->assertTrue($validator->validateRoom(new Room));
        $this->assertEmpty($validator->getErrors());
    }
}
