<?php
/**
 * Created by PhpStorm.
 * User: arnaudpagnier
 * Date: 12/09/2016
 * Time: 15:03
 */

namespace Tests\Fei\Service\Chat\Entity;


use Fei\Service\Chat\Entity\Message;
use Codeception\Test\Unit;
use Fei\Service\Chat\Entity\Room;

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

    public function testContexts()
    {
        
    }
}
