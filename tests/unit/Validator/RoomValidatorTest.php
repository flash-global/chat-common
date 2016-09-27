<?php

namespace Tests\Fei\Service\Chat\Validator;

use Codeception\Test\Unit;
use Doctrine\Common\Collections\ArrayCollection;
use Fei\Entity\Validator\Exception;
use Fei\Service\Chat\Entity\Message;
use Fei\Service\Chat\Entity\Room;
use Fei\Service\Chat\Validator\RoomValidator;

/**
 * Class RoomValidatorTest
 *
 * @package Tests\Fei\Service\Chat\Validator
 */
class RoomValidatorTest extends Unit
{
    public function testValidate()
    {
        $validator = new RoomValidator();

        $room = (new Room())
            ->setStatus(Room::ROOM_OPENED)
            ->setName('test')
            ->setKey('test');

        $this->assertTrue($validator->validate($room));
        $this->assertEmpty($validator->getErrors());
    }

    public function testValidateNoRoomEntity()
    {
        $validator = new RoomValidator();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'The Entity to validate must be an instance of ' . Room::class
        );

        $validator->validate(new Message());
    }

    public function testValidateKey()
    {
        $validator = new RoomValidator();

        $this->assertFalse($validator->validateKey(''));
        $this->assertEquals('The key cannot be empty', $validator->getErrors()['key'][0]);

        $validator = new RoomValidator();

        $this->assertFalse($validator->validateKey(array('toto')));
        $this->assertEquals('The key must be a string', $validator->getErrors()['key'][0]);

        $validator = new RoomValidator();

        $this->assertFalse($validator->validateKey(str_repeat('☃', 256)));
        $this->assertEquals('The key length has to be less or equal to 255', $validator->getErrors()['key'][0]);

        $validator = new RoomValidator();

        $this->assertTrue($validator->validateKey('a key'));
        $this->assertEmpty($validator->getErrors());
    }

    public function testValidateCreatedAt()
    {
        $validator = new RoomValidator();

        $this->assertFalse($validator->validateCreatedAt(''));
        $this->assertEquals('Creation date and time cannot be empty', $validator->getErrors()['createdAt'][0]);

        $validator = new RoomValidator();

        $this->assertFalse($validator->validateCreatedAt('test'));
        $this->assertEquals(
            'The creation date has to be and instance of \DateTime',
            $validator->getErrors()['createdAt'][0]
        );

        $validator = new RoomValidator();

        $this->assertTrue($validator->validateCreatedAt(new \DateTime()));
        $this->assertEmpty($validator->getErrors());
    }

    public function testValidateStatus()
    {
        $validator = new RoomValidator();

        $this->assertFalse($validator->validateStatus(null));
        $this->assertEquals('Status cannot be empty', $validator->getErrors()['status'][0]);

        $validator = new RoomValidator();

        $this->assertFalse($validator->validateStatus('toto'));
        $this->assertEquals('Status must be integer value : 0 or 1', $validator->getErrors()['status'][0]);

        $validator = new RoomValidator();

        $this->assertFalse($validator->validateStatus(2));
        $this->assertEquals(
            'Status is only 1 (open chat room) or 0 (closed chat room)',
            $validator->getErrors()['status'][0]
        );

        $validator = new RoomValidator();

        $this->assertTrue($validator->validateStatus(Room::ROOM_OPENED));
        $this->assertTrue($validator->validateStatus(Room::ROOM_CLOSED));
        $this->assertEmpty($validator->getErrors());
    }

    public function testValidateName()
    {
        $validator = new RoomValidator();

        $this->assertFalse($validator->validateName(''));
        $this->assertEquals('Chat room name cannot be empty', $validator->getErrors()['name'][0]);

        $validator = new RoomValidator();

        $this->assertFalse($validator->validateName(array('toto')));
        $this->assertEquals('The chat room name must be a string', $validator->getErrors()['name'][0]);

        $validator = new RoomValidator();

        $this->assertTrue($validator->validateName('toto'));
        $this->assertEmpty($validator->getErrors());
    }

    public function testValidateMessages()
    {
        $validator = new RoomValidator();

        $this->assertFalse($validator->validateMessages(''));
        $this->assertEquals(
            'Messages has to be and instance of \Doctrine\Common\Collections\ArrayCollection',
            $validator->getErrors()['messages'][0]
        );

        $validator = new RoomValidator();

        $messages = new ArrayCollection([new Message()]);
        $this->assertFalse($validator->validateMessages($messages));
        $this->assertEquals(
            'body: Chat message body cannot be empty; room: Message must be attached to a room',
            $validator->getErrors()['messages'][0]
        );

        $validator = new RoomValidator();

        $message = (new Message())
            ->setBody('test')
            ->setRoom(new Room());
        $messages = new ArrayCollection([$message]);
        $this->assertTrue($validator->validateMessages($messages));
        $this->assertEmpty($validator->getErrors());
    }
}
