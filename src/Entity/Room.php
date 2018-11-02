<?php

namespace Fei\Service\Chat\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fei\Entity\AbstractEntity;

/**
 * Class Location
 *
 * @package Fei\Service\Chat\Entity
 *
 * @Entity
 * @Table(
 *     name="rooms",
 *     indexes={ @Index(name="key_idx", columns={"key"}) }
 * )
 */
class Room extends AbstractEntity
{
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
     * @var string
     *
     * @Column(type="string", name="`token`")
     */
    protected $token;

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
     * @var ArrayCollection
     *
     * @OneToMany(targetEntity="RoomContext", mappedBy="room", cascade={"all"})
     */
    protected $contexts;

    /**
     * @var bool
     *
     * @Column(type="boolean", options={"default":0})
     */
    protected $private;

    /**
     * {@inheritdoc}
     */
    public function __construct($data = null)
    {
        $this->setMessages(new ArrayCollection());
        $this->setCreatedAt(new \DateTime());
        $this->setPrivate(false);

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
     * @param string $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
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
     * Get Contexts
     *
     * @return mixed
     */
    public function getContexts()
    {
        return $this->contexts;
    }

    /**
     * Get Private
     *
     * @return bool
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Set Private
     *
     * @param bool $private
     *
     * @return $this
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Set Contexts
     *
     * @param mixed $contexts
     *
     * @return $this
     */
    public function setContexts($contexts)
    {
        if ($contexts instanceof RoomContext) {
            $contexts->setRoom($this);
            $contexts = array($contexts);
        }
        if ($contexts instanceof \ArrayObject || is_array($contexts) || $contexts instanceof \Iterator) {
            foreach ($contexts as $key => $value) {
                if (!$value instanceof Context) {
                    $value = $value instanceof \stdClass ? (array) $value : $value;
                    if (is_int($key)
                        && is_array($value)
                        && array_key_exists('key', $value)
                        && array_key_exists('value', $value)
                    ) {
                        $contextData = array('key' => $value['key'], 'value' => $value['value']);
                        if (isset($value['id'])) {
                            $contextData['id'] = $value['id'];
                        }
                    } else {
                        $contextData = array('key' => $key, 'value' => $value);
                    }
                    $value = new RoomContext($contextData);
                }
                $value->setRoom($this);
                $this->contexts->add($value);
            }
        }
        return $this;
    }

    /**
     * Add a context
     *
     * @param RoomContext $context
     *
     * @return $this
     */
    public function addContext(RoomContext $context)
    {
        $context->setRoom($this);
        $this->contexts->add($context);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray($mapped = false)
    {
        $data = parent::toArray($mapped);

        if (!empty($data['messages'])) {
            $messages = [];
            foreach ($data['messages'] as $value) {
                $messages[] = $value->toArray();
            }
            $data['messages'] = $messages;
        }

        if (!empty($data['contexts'])) {
            $context = array();
            foreach ($data['contexts'] as $key => $value) {
                $context[$key] = $value->toArray();
                unset($context[$key]['room']);
            }
            $data['contexts'] = $context;
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function hydrate($data)
    {
        if (isset($data['messages']) && is_array($data['messages'])) {
            $messages = [];
            foreach ($data['messages'] as $message) {
                $messages[] = new Message($message);
            }
            $data['messages'] = new ArrayCollection($messages);
        }

        parent::hydrate($data);
    }
}
