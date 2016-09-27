<?php

namespace Tests\Fei\Service\Chat\Entity;

use Codeception\Test\Unit;
use Fei\Service\Chat\Entity\Message;
use Fei\Service\Chat\Entity\MessageTransformer;

/**
 * Class MessageTransformerTest
 *
 * @package Tests\Fei\Service\Chat\Entity
 */
class MessageTransformerTest extends Unit
{
    public function testTransform()
    {
        $now = new \DateTime();

        $message = (new Message())
            ->setId(1)
            ->setBody('body')
            ->setCreatedAt($now)
            ->setContext(['test' => 'test']);

        $this->assertEquals(
            [
                'id' => 1,
                'created_at' => $now->format(\DateTime::ISO8601),
                'body' => 'body',
                'context' => ['test' => 'test']
            ],
            (new MessageTransformer())->transform($message)
        );
    }
}
