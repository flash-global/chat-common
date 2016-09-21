<?php

namespace Fei\Service\Chat\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Fei\Entity\AbstractEntity;
use Fei\Service\Context\ContextAwareInterface;
use Fei\Service\Context\ContextAwareTrait;

/**
 * Class Location
 *
 * @package Fei\Service\Chat\Entity
 *
 * @Entity
 * @Table(name="rooms")
 */
class Room extends AbstractEntity implements ContextAwareInterface
{
    use ContextAwareTrait;

    const ROOM_CLOSED = 0;
    const ROOM_OPENED = 1;

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
     *
     * @Column(type="string", name="`key`", unique=true)
     */
    protected $key;

    /**
     * @var \DateTime
     *
     * @Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var int
     *
     * @Column(type="integer")
     */
    protected $status;

    /**
     * @var string
     *
     * @Column(type="string")
     */
    protected $name;

    /**
     * @var ArrayCollection
     *
     * @OneToMany(targetEntity="Message", mappedBy="room", cascade={"all"})
     */
    protected $messages;

    /**
     * @var ArrayCollection
     *
     * @OneToMany(targetEntity="RoomContext", mappedBy="room", cascade={"all"})
     */
    protected $contexts;

    /**
     * {@inheritdoc}
     */
    public function __construct($data = null)
    {
        $this->messages = new ArrayCollection();
        $this->contexts = new ArrayCollection();

        parent::__construct($data);
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
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param int $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
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
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param ArrayCollection $messages
     * @return $this
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * Returns the fully qualified class name of the implementation of the ContextInterface targeted by this entity
     *
     * @return string
     */
    public function getContextClassName()
    {
        return RoomContext::class;
    }
}
