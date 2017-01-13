<?php
namespace Tests\Fei\Service\Chat\Entity;

use Codeception\Test\Unit;
use Fei\Service\Chat\Entity\Message;
use Fei\Service\Chat\Entity\MessageContext;

class MessageContextTest extends Unit
{
    public function testRoomAccessors()
    {
        $messageContext = new MessageContext();
        $messageContext->setMessage(new Message());

        $this->assertEquals(new Message(), $messageContext->getMessage());
        $this->assertAttributeEquals($messageContext->getMessage(), 'message', $messageContext);
    }
}
