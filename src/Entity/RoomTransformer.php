<?php

namespace Fei\Service\Chat\Entity;

use League\Fractal\TransformerAbstract;

/**
 * Class LocationTransformer
 *
 * @package Fei\Service\Locate\Entity
 */
class RoomTransformer extends TransformerAbstract
{
    public function transform(Room $room)
    {
        $messageItems = [];
        $messageTransformer = new MessageTransformer();
        foreach ($room->getMessages() as $message) {
            $messageItems[$message->getId()] = $messageTransformer->transform($message);
        }

        return array(
            'id' => (int) $room->getId(),
            'created_at' => $room->getCreatedAt()->format(\DateTime::ISO8601),
            'key' => $room->getKey(),
            'name' => $room->getName(),
            'status' => $room->getStatus(),
            'messages' => $messageItems,
            'context' => $room->getContext(),
        );
    }
}

