<?php

namespace Fei\Service\Chat\Entity;


use Fei\Entity\EntityInterface;
use Fei\Service\Context\ContextInterface;
use Fei\Service\Context\ContextTrait;

/**
 * Class RoomContext
 * @package Fei\Service\Chat\Entity
 *
 * @Entity()
 * @Table(name="room_contexts", indexes={
 *     @Index(name="idx_contexts_keys", columns={"key"})
 * })
 */
class RoomContext implements ContextInterface
{
    use ContextTrait;

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
    public function setTargetEntity($entity)
    {
        $this->setRoom($entity);
    }
}
