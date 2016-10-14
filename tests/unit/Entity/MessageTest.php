<?php

namespace Tests\Fei\Service\Chat\Entity;

use Fei\Service\Chat\Entity\Message;
use Codeception\Test\Unit;
use Fei\Service\Chat\Entity\Room;

/**
 * Class MessageTest
 *
 * @package Tests\Fei\Service\Chat\Entity
 */
class MessageTest extends Unit
{
    public function testId()
    {
        $message = new Message();
        $message->setId(1);

        $this->assertEquals(1, $message->getId());
        $this->assertAttributeEquals($message->getId(), 'id', $message);
    }

    public function testBody()
    {
        $message = new Message();
        $message->setBody("Hello world !");

        $this->assertEquals("Hello world !", $message->getBody());
        $this->assertAttributeEquals($message->getBody(), 'body', $message);
    }

    public function testCreatedAt()
    {
        $message = new Message();
        $date = new \DateTime();
        $message->setCreatedAt($date);

        $this->assertEquals($date, $message->getCreatedAt());
        $this->assertAttributeEquals($message->getCreatedAt(), 'createdAt', $message);

        $message->setCreatedAt('');
        $this->assertInstanceOf('\DateTime', $message->getCreatedAt());

        $message->setCreatedAt('1977-06-08');
        $this->assertEquals(new \DateTime('1977-06-08'), $message->getCreatedAt());

        $message->setCreatedAt(null);
        $this->assertEquals(null, $message->getCreatedAt());

        $this->expectException(\Exception::class);
        $message->setCreatedAt('totoffl');
    }

    public function testRoom()
    {
        $message = new Message();

        $room = new Room();

        $message->setRoom($room);

        $this->assertEquals($room, $message->getRoom());
        $this->assertAttributeEquals($message->getRoom(), 'room', $message);
    }

    public function testUser()
    {
        $message = new Message();

        $message->setUser('user');

        $this->assertEquals('user', $message->getUser());
        $this->assertAttributeEquals($message->getUser(), 'user', $message);
    }

    public function testHydrate()
    {
        $now = new \DateTime();
        $message = new Message([
            'id' => 1,
            'body' => 'body',
            'createdAt' => $now,
            'user' => 'user',
            'context' => ['key' => 'value'],
            'room' => [
                'id' => 1,
                'key' => 'key',
                'name' => 'name',
                'createdAt' => $now,
                'status' => Room::ROOM_OPENED,
                'context' => ['key' => 'value']
            ]
        ]);

        $this->assertEquals(
            (new Message())
                ->setId(1)
                ->setBody('body')
                ->setCreatedAt($now)
                ->setUser('user')
                ->setContext(['key' => 'value'])
                ->setRoom(
                    (new Room())
                        ->setId(1)
                        ->setKey('key')
                        ->setName('name')
                        ->setCreatedAt($now)
                        ->setStatus(Room::ROOM_OPENED)
                        ->setContext(['key' => 'value'])
                ),
            $message
        );
    }

    public function testHydrateDataEmpty()
    {
        $message = new Message([]);

        $this->assertEquals(new Message(), $message);
    }

    public function testHydrateRoomEmpty()
    {
        $message = new Message(['room' => []]);

        $this->assertEquals((new Message())->setRoom(new Room()), $message);
    }

    public function testToArray()
    {
        $now = new \DateTime();

        $message = (new Message())
            ->setCreatedAt($now)
            ->setRoom((new Room())
                ->setCreatedAt($now));

        $this->assertEquals(
            [
                'id' => null,
                'body' => null,
                'user' => null,
                'created_at' => $now->format(\DateTime::RFC3339),
                'room_id' => null,
                'context' => null
            ],
            $message->toArray()
        );
    }

    public function testToArrayEmptyRoom()
    {
        $now = new \DateTime();

        $message = (new Message())
            ->setCreatedAt($now);

        $this->assertEquals(
            [
                'id' => null,
                'body' => null,
                'user' => null,
                'created_at' => $now->format(\DateTime::RFC3339),
                'context' => null,
                'room' => null
            ],
            $message->toArray()
        );
    }
}
