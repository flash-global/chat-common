<?php

namespace Fei\Service\Chat\Entity;

use Fei\Service\Context\ContextInterface;
use Fei\Service\Context\ContextTrait;

/**
 * Class MessageContext
 * @package Fei\Service\Chat\Entity
 *
 * @Entity()
 * @Table(name="message_contexts", indexes={
 *     @Index(name="idx_contexts_keys", columns={"key"})
 * })
 */
class MessageContext implements ContextInterface
{
    use ContextTrait;

    /**
     * @var Message
     *
     * @ManyToOne(targetEntity="Message", inversedBy="contexts")
     */
    protected $message;

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Message $message
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function setTargetEntity($entity)
    {
        $this->setMessage($entity);
    }
}
