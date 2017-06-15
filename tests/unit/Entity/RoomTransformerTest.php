<?php

namespace Tests\Fei\Service\Chat\Entity;

use Codeception\Test\Unit;
use Fei\Service\Chat\Entity\Message;
use Fei\Service\Chat\Entity\Room;
use Fei\Service\Chat\Entity\RoomTransformer;

/**
 * Class RoomTransformerTest
 *
 * @package Tests\Fei\Service\Chat\Entity
 */
class RoomTransformerTest extends Unit
{
    public function testTransform()
    {
        $now = new \DateTime();

        $message = (new Message())
            ->setId(1)
            ->setBody('body')
            ->setCreatedAt($now)
            ->setUsername('user')
            ->setContexts(['test' => 'test']);

        $room = (new Room())
            ->setId(1)
            ->setCreatedAt($now)
            ->setKey('a key')
            ->setName('a name')
            ->setStatus(Room::ROOM_OPENED)
            ->setContexts(['test' => 'test'])
            ->addMessage($message);

        $this->assertEquals(
            [
                'id' => 1,
                'created_at' => $now->format(\DateTime::ISO8601),
                'key' => 'a key',
                'name' => 'a name',
                'status' => 1,
                'contexts' => ['test' => 'test'],
                'messages' => [
                    1 => [
                        'id' => 1,
                        'created_at' => $now->format(\DateTime::ISO8601),
                        'body' => 'body',
                        'username' => 'user',
                        'display_username' => null,
                        'room' => [
                            'id' => 1,
                            'created_at' => $now->format(\DateTime::ISO8601),
                            'key' => 'a key',
                            'name' => 'a name',
                            'status' => 1,
                            'messages' => [],
                            'contexts' => ['test' => 'test'],
                        ],
                        'contexts' => ['test' => 'test']
                    ]
                ]
            ],
            (new RoomTransformer(true))->transform($room)
        );
    }
}
