<?php
namespace Fei\Service\Chat\Entity;

use Fei\Entity\AbstractEntity;

/**
 * Class Unread
 *
 * @package Fei\Service\Chat\Entity
 *
 * @Entity
 * @Table(
 *     name="unread_messages",
 *     indexes={ @Index(name="username_idx", columns={"username"}) }
 * )
 */
class Unread extends AbstractEntity
{
    /**
     * @var Message
     *
     * @Id
     * @ManyToOne(targetEntity="Message", cascade={"persist"})
     * @JoinColumn(name="message_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $message;

    /**
     * @var string
     *
     * @Id
     * @Column(type="string")
     */
    protected $username;

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

    /**
     * Get Username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set Username
     *
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }
}
