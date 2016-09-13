<?php namespace Fei\Service\Chat\Validator;

use Doctrine\Common\Collections\ArrayCollection;
use Fei\Entity\EntityInterface;
use Fei\Entity\Exception;
use Fei\Entity\Validator\AbstractValidator;
use Fei\Service\Chat\Entity\Message;
use Fei\Service\Chat\Entity\Room;

class MessageValidator extends AbstractValidator
{

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
            throw new Exception('The Entity to validate must be an instance of \Fei\Service\Locate\Entity\Location');
        }

        $this->validateBody($entity->getBody());
        $this->validateCreateAt($entity->getCreatedAt());
        $this->validateRoom($entity->getRoom());
        $this->validateContext($entity->getContext());
        $errors = $this->getErrors();

        return empty($errors);
    }

    public function validateBody($body)
    {
        if (empty($body)) {
            $this->addError('body', 'chat Message body cannot be null');
            return false;
        }
        return true;
    }

    public function validateCreateAt($createdAt)
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

    public function validateRoom($room)
    {
        if (!$room instanceof Room) {
            $this->addError(
                'room',
                'Message must be attached to a room'
            );
            return false;
        }

        return true;
    }

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

            return true;
        }
    }
}
