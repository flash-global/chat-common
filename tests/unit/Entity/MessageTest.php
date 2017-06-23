<?php

namespace Tests\Fei\Service\Chat\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fei\Service\Chat\Entity\Message;
use Codeception\Test\Unit;
use Fei\Service\Chat\Entity\MessageContext;
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

    public function testDisplayUsername()
    {
        $message = new Message();
        $message->setDisplayUsername("username");

        $this->assertEquals('username', $message->getDisplayUsername());
        $this->assertAttributeEquals($message->getDisplayUsername(), 'displayUsername', $message);
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

    public function testUsername()
    {
        $message = new Message();

        $message->setUsername('user');

        $this->assertEquals('user', $message->getUsername());
        $this->assertAttributeEquals($message->getUsername(), 'username', $message);
    }

    public function testHydrate()
    {
        $now = new \DateTime();
        $message = new Message([
            'id' => 1,
            'body' => 'body',
            'createdAt' => $now,
            'username' => 'user',
            'contexts' => ['key' => 'value'],
            'room' => [
                'id' => 1,
                'key' => 'key',
                'name' => 'name',
                'createdAt' => $now,
                'status' => Room::ROOM_OPENED,
                'contexts' => ['key' => 'value']
            ]
        ]);

        $this->assertEquals(
            (new Message())
                ->setId(1)
                ->setBody('body')
                ->setCreatedAt($now)
                ->setUsername('user')
                ->setContexts(['key' => 'value'])
                ->setRoom(
                    (new Room())
                        ->setId(1)
                        ->setKey('key')
                        ->setName('name')
                        ->setCreatedAt($now)
                        ->setStatus(Room::ROOM_OPENED)
                        ->setContexts(['key' => 'value'])
                ),
            $message
        );
    }

    public function testSettingContextsWhenTheParamIsAMessageContext()
    {
        $expected = new ArrayCollection([new MessageContext(['key' => 'key', 'value' => 'value'])]);

        $message = new Message();
        $message->setContexts($expected->get(0));

        $this->assertEquals($expected, $message->getContexts());
    }

    public function testSettingContextsWhenTheParamIsAnArrayOfKeyValuePair()
    {
        $message = new Message();
        $message->setContexts([
            ['key' => 'key', 'value' => 'value']
        ]);

        $this->assertEquals(
            new ArrayCollection([
                (new MessageContext(['key' => 'key', 'value' => 'value']))->setMessage($message)
            ]),
            $message->getContexts()
        );
    }

    public function testAddContext()
    {
        $message = new Message();

        $message->addContext(new MessageContext(['key' => 'k', 'value' => 'v']));

        $this->assertEquals(
            new ArrayCollection([
                (new MessageContext(['key' => 'k', 'value' => 'v']))
                    ->setMessage($message)
            ]),
            $message->getContexts()
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
                ->setCreatedAt($now)
            )->addContext(new MessageContext(['key' => 'test', 'value' => 'test']));

        $this->assertEquals(
            [
                'id' => null,
                'body' => null,
                'username' => null,
                'display_username' => null,
                'created_at' => $now->format(\DateTime::RFC3339),
                'room_id' => null,
                'contexts' => [
                    [
                        'id' => null,
                        'key' => 'test',
                        'value' => 'test',
                    ]
                ]
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
                'username' => null,
                'display_username' => null,
                'created_at' => $now->format(\DateTime::RFC3339),
                'contexts' => [],
                'room' => null
            ],
            $message->toArray()
        );
    }
}
