<?php

namespace Fei\Service\Chat\Entity;


use Fei\Entity\EntityInterface;
use Fei\Service\Context\AbstractContext;

/**
 * Class RoomContext
 * @package Fei\Service\Chat\Entity
 *
 * @Entity()
 * @Table(name="room_contexts", indexes={
 *     @Index(name="idx_contexts_keys", columns={"key"})
 * })
 */
class RoomContext extends AbstractContext
{
    /**
     * @var Room
     * @ManyToOne(targetEntity="Room", inversedBy="contexts")
     */
    protected $room;

    /**
     * @return Room
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param Room $room
     */
    public function setRoom(Room $room)
    {
        $this->room = $room;
    }

    /**
     * Set the targeted entity by the context
     *
     * @param EntityInterface $entity
     */
    public function setTargetEntity(EntityInterface $entity)
    {
        $this->setRoom($entity);
    }
}
