<?php

namespace Tests\Fei\Service\Chat\Entity;

use Codeception\Test\Unit;
use Doctrine\Common\Collections\ArrayCollection;
use Fei\Service\Chat\Entity\Message;
use Fei\Service\Chat\Entity\Room;

/**
 * Class RoomTest
 *
 * @package Tests\Fei\Service\Chat\Entity
 */
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
        $this->assertSame($room, $firstmessage->getRoom());
        $this->assertSame($secondmessage, $room->getMessages()->next());
        $this->assertSame($room, $secondmessage->getRoom());
    }

    public function testAddMessage()
    {
        $room = new Room();

        $room->setMessages(new ArrayCollection([new Message()]));
        $room->addMessage(new Message());

        $this->assertEquals(2, $room->getMessages()->count());
        $this->assertSame($room, $room->getMessages()->next()->getRoom());
    }

    public function testHydrate()
    {
        $now = new \DateTime();

        $room = new Room([
            'id' => 1,
            'key' => 'key',
            'name' => 'name',
            'createdAt' => $now,
            'status' => Room::ROOM_OPENED,
            'contexts' => ['key' => 'value'],
            'messages' => [
                [
                    'id' => 1,
                    'body' => 'body',
                    'createdAt' => $now,
                    'user' => 'user',
                    'contexts' => ['key' => 'value']
                ]
            ]
        ]);

        $this->assertEquals(
            (new Room())
                ->setId(1)
                ->setKey('key')
                ->setName('name')
                ->setCreatedAt($now)
                ->setStatus(Room::ROOM_OPENED)
                ->setContexts(['key' => 'value'])
                ->addMessage(
                    (new Message())
                    ->setId(1)
                    ->setBody('body')
                    ->setCreatedAt($now)
                    ->setUser('user')
                    ->setContexts(['key' => 'value'])
                ),
            $room
        );
    }

    public function testHydrateDataEmpty()
    {
        $message = new Room([]);

        $this->assertEquals(new Room(), $message);
    }

    public function testHydrateRoomEmpty()
    {
        $message = new Room(['messages' => []]);

        $this->assertEquals((new room()), $message);
    }

    public function testToArray()
    {
        $now = new \DateTime();

        $room = (new Room())
            ->setCreatedAt($now)
            ->addMessage((new Message())->setCreatedAt($now));

        $this->assertEquals(
            [
                'id' => $room->getId(),
                'key' => $room->getKey(),
                'created_at' => $room->getCreatedAt()->format(\DateTime::RFC3339),
                'status' => $room->getStatus(),
                'name' => $room->getName(),
                'messages' => [
                    [
                        'id' => null,
                        'body' => null,
                        'user' => null,
                        'created_at' => $now->format(\DateTime::RFC3339),
                        'room_id' => $room->getId(),
                        'contexts' => new ArrayCollection()
                    ]
                ],
                'contexts' => new ArrayCollection()
            ],
            $room->toArray()
        );
    }

    public function testToArrayEmptyMessage()
    {
        $now = new \DateTime();

        $room = (new Room())->setCreatedAt($now);

        $this->assertEquals(
            [
                'id' => $room->getId(),
                'key' => $room->getKey(),
                'created_at' => $room->getCreatedAt()->format(\DateTime::RFC3339),
                'status' => $room->getStatus(),
                'name' => $room->getName(),
                'messages' => [],
                'contexts' => new ArrayCollection()
            ],
            $room->toArray()
        );
    }
}
