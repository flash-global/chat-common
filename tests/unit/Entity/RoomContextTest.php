<?php
namespace Tests\Fei\Service\Chat\Entity;

use Codeception\Test\Unit;
use Fei\Service\Chat\Entity\Room;
use Fei\Service\Chat\Entity\RoomContext;

class RoomContextTest extends Unit
{
    public function testRoomAccessors()
    {
        $roomContext = new RoomContext();
        $roomContext->setRoom(new Room());

        $this->assertEquals(new Room(), $roomContext->getRoom());
        $this->assertAttributeEquals($roomContext->getRoom(), 'room', $roomContext);
    }
}
