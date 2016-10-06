<?php

namespace Fei\Service\Chat\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fei\Entity\AbstractEntity;
use Fei\Service\Context\ContextAwareTrait;

/**
 * Class Location
 *
 * @package Fei\Service\Chat\Entity
 *
 * @Entity
 * @Table(name="rooms")
 */
class Room extends AbstractEntity
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
    protected $status = Room::ROOM_OPENED;

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
     * {@inheritdoc}
     */
    public function __construct($data = null)
    {
        $this->setMessages(new ArrayCollection());
        $this->setCreatedAt(new \DateTime());

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
     * @param string $key
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
     *
     * @return $this
     */
    public function setMessages(ArrayCollection $messages = null)
    {
        if (!empty($messages)) {
            foreach ($messages as $message) {
                $message->setRoom($this);
            }
        }

        $this->messages = $messages;

        return $this;
    }

    /**
     * Add a message
     *
     * @param Message $message
     *
     * @return $this
     */
    public function addMessage(Message $message)
    {
        $message->setRoom($this);
        $this->messages->add($message);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hydrate($data)
    {
        if (isset($data['messages']) && is_array($data['messages'])) {
            $data['messages'] = new ArrayCollection($data['messages']);
        }

        if (isset($data['context']) && is_string($data['context'])) {
            $data['context'] = json_decode($data['context'], true);
        }

        return parent::hydrate($data);
    }
}
