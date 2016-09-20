<?php

namespace Fei\Service\Chat\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fei\Service\Context\ContextAwareInterface;
use Fei\Service\Context\ContextAwareTrait;

/**
 * Class Location
 *
 * @package Fei\Service\Chat\Entity
 *
 * @Entity
 * @Table(name="messages")
 */
class Message implements ContextAwareInterface
{
    use ContextAwareTrait;

    /**
     * @var int
     *
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $body;

    /**
     * @var \DateTime
     * @Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var Room
     *
     * @ManyToOne(targetEntity="Room")
     * @JoinColumn(name="room_id", referencedColumnName="id")
     */
    protected $room;

    /**
     * @var ArrayCollection
     *
     * @OneToMany(targetEntity="MessageContext", mappedBy="message", cascade={"all"})
     */
    protected $contexts;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->contexts = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        if (is_string($createdAt)) {
            $createdAt = new \DateTime($createdAt);
        }

        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param mixed $room
     * @return $this
     */
    public function setRoom($room)
    {
        $this->room = $room;
        return $this;
    }

    /**
     * Returns the fully qualified class name of the implementation of the ContextInterface targeted by this entity
     *
     * @return string
     */
    public function getContextClassName()
    {
        return MessageContext::class;
    }
}
