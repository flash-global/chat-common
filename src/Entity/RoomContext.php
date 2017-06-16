<?php

namespace Fei\Service\Chat\Entity;

/**
 * Class RoomContext
 *
 * @package Fei\Service\Chat\Entity
 *
 * @Entity
 */
class RoomContext extends Context
{
    /**
     * @var Room
     *
     * @ManyToOne(targetEntity="Room", inversedBy="contexts")
     * @JoinColumn(name="room_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $room;

    /**
     * Get Room
     *
     * @return Room
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set Room
     *
     * @param Room $room
     *
     * @return $this
     */
    public function setRoom(Room $room)
    {
        $this->room = $room;

        return $this;
    }
}
