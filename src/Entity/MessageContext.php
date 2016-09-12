<?php

namespace Fei\Service\Chat\Entity;


use Fei\Entity\EntityInterface;
use Fei\Service\Context\AbstractContext;

/**
 * Class MessageContext
 * @package Fei\Service\Chat\Entity
 *
 * @Entity()
 * @Table(name="message_contexts", indexes={
 *     @Index(name="idx_contexts_keys", columns={"key"})
 * })
 */
class MessageContext extends AbstractContext
{

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
     * Set the targeted entity by the context
     *
     * @param EntityInterface $entity
     */
    public function setTargetEntity(EntityInterface $entity)
    {
        $this->setMessage($entity);
    }
}
