<?php

namespace Fei\Service\Chat\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fei\Entity\AbstractEntity;

/**
 * Class Message
 *
 * @package Fei\Service\Chat\Entity
 *
 * @Entity
 * @Table(
 *     name="messages",
 *     indexes={ @Index(name="user_idx", columns={"username"}) }
 * )
 */
class Message extends AbstractEntity
{
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
     * @Column(type="text")
     */
    protected $body;

    /**
     * @var \DateTime
     * @Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var string
     * @Column(type="string")
     */
    protected $username;

    /**
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $displayUsername;

    /**
     * @var Room
     *
     * @ManyToOne(targetEntity="Room")
     * @JoinColumn(name="room_id", referencedColumnName="id", nullable=false)
     */
    protected $room;

    /**
     * @var ArrayCollection
     *
     * @OneToMany(targetEntity="MessageContext", mappedBy="message", cascade={"all"})
     */
    protected $contexts;

    /**
     * {@inheritdoc}
     */
    public function __construct($data = null)
    {
        $this->setCreatedAt(new \DateTime());
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

    /**
     * Get DisplayUsername
     *
     * @return string
     */
    public function getDisplayUsername()
    {
        return $this->displayUsername;
    }

    /**
     * Set DisplayUsername
     *
     * @param string $displayUsername
     *
     * @return $this
     */
    public function setDisplayUsername($displayUsername)
    {
        $this->displayUsername = $displayUsername;

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
    public function setRoom(Room $room)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get Contexts
     *
     * @return ArrayCollection
     */
    public function getContexts()
    {
        return $this->contexts;
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
        if ($contexts instanceof MessageContext) {
            $contexts->setMessage($this);
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
                    $value = new MessageContext($contextData);
                }
                $value->setMessage($this);
                $this->contexts->add($value);
            }
        }
        return $this;
    }

    /**
     * Add a context
     *
     * @param MessageContext $context
     *
     * @return $this
     */
    public function addContext(MessageContext $context)
    {
        $context->setMessage($this);
        $this->contexts->add($context);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray($mapped = false)
    {
        $data = parent::toArray($mapped);

        if (!empty($data['room']) && $data['room'] instanceof Room) {
            $data['room_id'] = $data['room']->getId();
            unset($data['room']);
        }

        if (!empty($data['contexts'])) {
            $context = array();
            foreach ($data['contexts'] as $key => $value) {
                $context[$key] = $value->toArray();
                unset($context[$key]['message']);
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
        if (isset($data['room']) && is_array($data['room'])) {
            $data['room'] = new Room($data['room']);
        }

        parent::hydrate($data);
    }
}
