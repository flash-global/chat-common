<?php

namespace Tests\Fei\Service\Chat\Entity;

use Codeception\Test\Unit;
use Fei\Service\Chat\Entity\Message;
use Fei\Service\Chat\Entity\Unread;

/**
 * Class UnreadTest
 *
 * @package Tests\Fei\Service\Chat\Entity
 */
class UnreadTest extends Unit
{
    public function testMessageAccessors()
    {
        $message = new Message([
            'id' => 1
        ]);

        $unread = new Unread();
        $unread->setMessage($message);

        $this->assertEquals($message, $unread->getMessage());
        $this->assertAttributeEquals($unread->getMessage(), 'message', $unread);
    }

    public function testUsernameAccessors()
    {
        $unread = new Unread();
        $unread->setUsername('user');

        $this->assertEquals('user', $unread->getUsername());
        $this->assertAttributeEquals($unread->getUsername(), 'user', $unread);
    }
}
