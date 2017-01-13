<?php

namespace Fei\Service\Chat\Entity;

use League\Fractal\TransformerAbstract;

/**
 * Class MessageTransformer
 *
 * @package Fei\Service\Chat\Entity
 */
class MessageTransformer extends TransformerAbstract
{
    public function transform(Message $message)
    {

        $contextItems = array();

        foreach ($message->getContexts() as $contextItem) {
            $contextItems[$contextItem->getKey()] = $contextItem->getValue();
        }

        return array(
            'id' => (int) $message->getId(),
            'created_at' => $message->getCreatedAt()->format(\DateTime::ISO8601),
            'body' => $message->getBody(),
            'user' => $message->getUser(),
            'room' => $message->getRoom() ? (new RoomTransformer())->transform($message->getRoom()) : [],
            'contexts' => $contextItems
        );
    }
}
