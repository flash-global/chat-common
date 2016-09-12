<?php

namespace Tests\Fei\Service\Chat\Entity;

use Codeception\Test\Unit;
use Doctrine\Common\Collections\ArrayCollection;
use Fei\Service\Chat\Entity\Message;
use Fei\Service\Chat\Entity\Room;

class RoomTest extends Unit
{
    public function testId()
    {
        $room = new Room();
        $room->setId(1);

        $this->assertEquals(1, $room->getId());
        $this->assertAttributeEquals($room->getId(), 'id', $room);
    }

    public function testKey()
    {
        $room = new Room();
        $room->setKey('arandomkey');

        $this->assertEquals('arandomkey', $room->getKey());
        $this->assertAttributeEquals($room->getKey(), 'key', $room);
    }

    public function testCreatedAt()
    {
        $room = new Room();
        $date = new \DateTime();
        $room->setCreatedAt($date);

        $this->assertEquals($date, $room->getCreatedAt());
        $this->assertAttributeEquals($room->getCreatedAt(), 'createdAt', $room);

        $room->setCreatedAt('');
        $this->assertInstanceOf('\DateTime', $room->getCreatedAt());

        $room->setCreatedAt('1977-06-08');
        $this->assertEquals(new \DateTime('1977-06-08'), $room->getCreatedAt());

        $room->setCreatedAt(null);
        $this->assertEquals(null, $room->getCreatedAt());

        $this->expectException(\Exception::class);
        $room->setCreatedAt('totoffl');
    }

    public function testName()
    {
        $room = new Room();
        $room->setName('arandomname');

        $this->assertEquals('arandomname', $room->getName());
        $this->assertAttributeEquals($room->getName(), 'name', $room);
    }

    public function testStatus()
    {
        $room = new Room();
        $room->setStatus(1);

        $this->assertEquals(1, $room->getStatus());
        $this->assertAttributeEquals($room->getStatus(), 'status', $room);
    }

    public function testMessages()
    {
        $room = new Room();
        $messages = new ArrayCollection();
        $firstmessage = new Message();
        $secondmessage = new Message();
        $messages->add($firstmessage);
        $messages->add($secondmessage);
        $room->setMessages($messages);

        $this->assertSame($messages, $room->getMessages());
        $this->assertAttributeSame($room->getMessages(), 'messages', $room);
        $this->assertSame($firstmessage, $room->getMessages()->first());
        $this->assertSame($secondmessage, $room->getMessages()->next());
    }
}
