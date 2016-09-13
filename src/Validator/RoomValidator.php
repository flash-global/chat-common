<?php namespace Fei\Service\Chat\Validator;

use Doctrine\Common\Collections\ArrayCollection;
use Fei\Entity\EntityInterface;
use Fei\Entity\Validator\AbstractValidator;
use Fei\Entity\Validator\Exception;
use Fei\Service\Chat\Entity\Room;

class RoomValidator extends AbstractValidator
{
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
            throw new Exception('The Entity to validate must be an instance of \Fei\Service\Locate\Entity\Location');
        }

        $this->validateKey($entity->getKey());
        $this->validateCreatedAt($entity->getCreatedAt());
        $this->validateStatus($entity->getStatus());
        $this->validateName($entity->getName());
        $this->validateContext($entity->getContext());
        //$this->validateMessages($entity->getMessages()); // is it useful and relevant to validate Messages ?

        $errors = $this->getErrors();

        return empty($errors);
    }

    /**
     * @param $key
     * @return bool
     */
    public function validateKey($key)
    {
        if (empty($key)) {
            $this->addError('key', 'The key cannot be empty');
            return false;
        }

        if (mb_strlen($key, 'UTF-8') > 255) {
            $this->addError('key', 'The key length has to be less or equal to 255');
            return false;
        }

        return true;
    }

    /**
     * @param $createdAt
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
     * @param $status
     * @return bool
     */
    public function validateStatus($status)
    {
        if (empty($status)) {
            $this->addError('status', 'Status cannot be empty');
            return false;
        }

        if (!is_int($status)) {
            $this->addError('status', 'Status must be integer value : 0 or 1');
            return false;
        }

        if ($status != 0 || $status != 1) {
            $this->addError('status', 'Status is only 1 (open chat room) or 0 (closed chat room)');
            return false;
        }

        return true;
    }

    /**
     * @param $name
     * @return bool
     */
    public function validateName($name)
    {
        if (empty($status)) {
            $this->addError('name', 'Chat room name cannot be empty');
        }
        return true;
    }

    /**
     * @param $messages
     * @return bool
     */
    public function validateMessages($messages)
    {
        if (!$messages instanceof ArrayCollection) {
            $this->addError(
                'contexts',
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

    /**
     * @param $context
     * @return bool
     * @throws Exception
     */
    public function validateContext($context)
    {
        if (!$context instanceof ArrayCollection) {
            $this->addError(
                'contexts',
                'Context has to be and instance of \Doctrine\Common\Collections\ArrayCollection'
            );
            return false;
        }

        if (!$context->isEmpty()) {
            $validator = new ContextValidator();
            foreach ($context as $value) {
                $validator->validate($value);
            }

            if (!empty($validator->getErrors())) {
                $this->addError('contexts', $validator->getErrorsAsString());
                return false;
            }
        }

        return true;
    }
}
