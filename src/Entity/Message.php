<?php

namespace Fei\Service\Chat\Entity;

use Fei\Entity\AbstractEntity;
use Fei\Entity\EntityInterface;
use Fei\Service\Context\ContextAwareTrait;

/**
 * Class Location
 *
 * @package Fei\Service\Chat\Entity
 *
 * @Entity
 * @Table(
 *     name="messages",
 *     indexes={ @Index(name="user_idx", columns={"user"}) }
 * )
 */
class Message extends AbstractEntity
{
    use ContextAwareTrait {
        hydrate as protected hydrateContext;
    }

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
    protected $user;

    /**
     * @var Room
     *
     * @ManyToOne(targetEntity="Room")
     * @JoinColumn(name="room_id", referencedColumnName="id")
     */
    protected $room;

    /**
     * {@inheritdoc}
     */
    public function __construct($data = null)
    {
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
     * Get User
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set User
     *
     * @param string $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

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
     * {@inheritdoc}
     */
    public function toArray($mapped = false)
    {
        $data = parent::toArray($mapped);

        if (!empty($data['room']) && $data['room'] instanceof Room) {
            $data['room_id'] = $data['room']->getId();
            unset($data['room']);
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

        $this->hydrateContext($data);
    }
}
