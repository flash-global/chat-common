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
    /**
     * @var bool
     */
    protected $withMessages;

    /**
     * RoomTransformer constructor.
     *
     * @param bool $witMessages
     */
    public function __construct($witMessages = false)
    {
        $this->withMessages = $witMessages;
    }

    public function transform(Room $room)
    {
        $contextItems = array();

        foreach ($room->getContexts() as $contextItem) {
            $contextItems[$contextItem->getKey()] = $contextItem->getValue();
        }

        $transformed = array(
            'id' => (int) $room->getId(),
            'created_at' => $room->getCreatedAt()->format(\DateTime::ISO8601),
            'key' => $room->getKey(),
            'name' => $room->getName(),
            'status' => $room->getStatus(),
            'messages' => [],
            'contexts' => $contextItems
        );

        if ($this->withMessages) {
            $messageItems = [];
            $messageTransformer = new MessageTransformer();
            foreach ($room->getMessages()->toArray() as $message) {
                $messageItems[$message->getId()] = $messageTransformer->transform($message);
            }

            $transformed['messages'] = $messageItems;
        }

        return $transformed;
    }
}
