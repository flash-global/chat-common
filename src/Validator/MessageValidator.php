<?php

namespace Fei\Service\Chat\Validator;

use Fei\Entity\EntityInterface;
use Fei\Entity\Validator\Exception;
use Fei\Entity\Validator\AbstractValidator;
use Fei\Service\Chat\Entity\Message;
use Fei\Service\Chat\Entity\Room;
use Fei\Service\Context\Validator\ContextAwareValidatorTrait;

class MessageValidator extends AbstractValidator
{
    use ContextAwareValidatorTrait;

    /**
     * Validate a Message instance
     *
     * @param EntityInterface $entity
     *
     * @return bool
     *
     * @throws Exception
     */
    public function validate(EntityInterface $entity)
    {
        if (!$entity instanceof Message) {
            throw new Exception(
                sprintf('The Entity to validate must be an instance of %s', Message::class)
            );
        }

        $this->validateBody($entity->getBody());
        $this->validateCreatedAt($entity->getCreatedAt());
        $this->validateRoom($entity->getRoom());
        $this->validateContext($entity->getContext());
        $errors = $this->getErrors();

        return empty($errors);
    }

    /**
     * Validate body
     *
     * @param mixed $body
     *
     * @return bool
     */
    public function validateBody($body)
    {
        if (empty($body)) {
            $this->addError('body', 'Chat message body cannot be empty');
            return false;
        }

        if (!is_string($body)) {
            $this->addError('body', 'The chat message body  must be a string');
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
     * Validate Room
     *
     * @param mixed $room
     *
     * @return bool
     */
    public function validateRoom($room)
    {
        if (!$room instanceof Room) {
            $this->addError('room', 'Message must be attached to a room');
            return false;
        }

        return true;
    }
}
