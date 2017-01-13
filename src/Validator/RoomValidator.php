<?php

namespace Fei\Service\Chat\Validator;

use Doctrine\Common\Collections\ArrayCollection;
use Fei\Entity\EntityInterface;
use Fei\Entity\Validator\AbstractValidator;
use Fei\Entity\Validator\Exception;
use Fei\Service\Chat\Entity\Room;
use Fei\Service\Context\Validator\ContextAwareValidatorTrait;

/**
 * Class RoomValidator
 *
 * @package Fei\Service\Chat\Validator
 */
class RoomValidator extends AbstractValidator
{
    use ContextAwareValidatorTrait;

    /**
     * Validate a Room instance
     *
     * @param EntityInterface $entity
     *
     * @return bool
     *
     * @throws Exception
     */
    public function validate(EntityInterface $entity)
    {
        if (!$entity instanceof Room) {
            throw new Exception(
                sprintf('The Entity to validate must be an instance of %s', Room::class)
            );
        }

        $this->validateKey($entity->getKey());
        $this->validateCreatedAt($entity->getCreatedAt());
        $this->validateStatus($entity->getStatus());
        $this->validateName($entity->getName());
        //$this->validateContext($entity->getContexts());
        $this->validateMessages($entity->getMessages());

        $errors = $this->getErrors();

        return empty($errors);
    }

    /**
     * Validate key
     *
     * @param mixed $key
     *
     * @return bool
     */
    public function validateKey($key)
    {
        if (empty($key)) {
            $this->addError('key', 'The key cannot be empty');
            return false;
        }

        if (!is_string($key)) {
            $this->addError('key', 'The key must be a string');
            return false;
        }

        if (mb_strlen($key, 'UTF-8') > 255) {
            $this->addError('key', 'The key length has to be less or equal to 255');
            return false;
        }

        return true;
    }

    /**
     * Validate createdAt
     *
     * @param mixed $createdAt
     *
     * @return bool
     */
    public function validateCreatedAt($createdAt)
    {
        if (empty($createdAt)) {
            $this->addError('createdAt', 'Creation date and time cannot be empty');
            return false;
        }

        if (!$createdAt instanceof \DateTime) {
            $this->addError('createdAt', 'The creation date has to be and instance of \DateTime');
            return false;
        }

        return true;
    }

    /**
     * Validate Status
     *
     * @param mixed $status
     *
     * @return bool
     */
    public function validateStatus($status)
    {
        if (is_null($status)) {
            $this->addError('status', 'Status cannot be null');
            return false;
        }

        if (!is_numeric($status)) {
            $this->addError('status', 'Status must be integer value : 0 or 1');
            return false;
        }

        if ($status != Room::ROOM_CLOSED && $status != Room::ROOM_OPENED) {
            $this->addError('status', 'Status is only 1 (open chat room) or 0 (closed chat room)');
            return false;
        }

        return true;
    }

    /**
     * Validate name
     *
     * @param mixed $name
     *
     * @return bool
     */
    public function validateName($name)
    {
        if (empty($name)) {
            $this->addError('name', 'The chat room name cannot be empty');
            return false;
        }

        if (!is_string($name)) {
            $this->addError('name', 'The chat room name must be a string');
            return false;
        }

        if (mb_strlen($name, 'UTF-8') > 255) {
            $this->addError('name', 'The chat room name length has to be less or equal to 255');
            return false;
        }

        return true;
    }

    /**
     * Validate messages
     *
     * @param mixed $messages
     *
     * @return bool
     */
    public function validateMessages($messages)
    {
        if (!$messages instanceof ArrayCollection) {
            $this->addError(
                'messages',
                'Messages has to be and instance of \Doctrine\Common\Collections\ArrayCollection'
            );
            return false;
        }

        if (!$messages->isEmpty()) {
            $validator = new MessageValidator();
            foreach ($messages as $value) {
                $validator->validate($value);
            }

            if (!empty($validator->getErrors())) {
                $this->addError('messages', $validator->getErrorsAsString());
                return false;
            }
        }

        return true;
    }
}
