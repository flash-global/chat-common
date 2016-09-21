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
        $contextItems = [];
        foreach ($message->getContext() as $contextItem) {
            $contextItems[$contextItem->getKey()] = $contextItem->getValue();
        }

        return array(
            'id' => (int) $message->getId(),
            'created_at' => $message->getCreatedAt()->format(\DateTime::ISO8601),
            'body' => $message->getBody(),
            'context' => $contextItems,
        );
    }
}
