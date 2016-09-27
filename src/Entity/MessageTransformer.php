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
        return array(
            'id' => (int) $message->getId(),
            'created_at' => $message->getCreatedAt()->format(\DateTime::ISO8601),
            'body' => $message->getBody(),
            'context' => $message->getContext()
        );
    }
}
