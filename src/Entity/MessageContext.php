<?php

namespace Fei\Service\Chat\Entity;

/**
 * Class MessageContext
 *
 * @package Fei\Service\Chat\Entity
 *
 * @Entity
 */
class MessageContext extends Context
{
    /**
     * @var Message
     *
     * @ManyToOne(targetEntity="Message", inversedBy="contexts")
     * @JoinColumn(name="message_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $message;

    /**
     * Get Message
     *
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set Message
     *
     * @param Message $message
     *
     * @return $this
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;

        return $this;
    }
}
