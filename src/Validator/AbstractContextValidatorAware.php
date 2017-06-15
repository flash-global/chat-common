<?php

namespace Fei\Service\Chat\Validator;

use Doctrine\Common\Collections\Collection;
use Fei\Entity\Validator\AbstractValidator;

/**
 * Trait ContextValidatorAwareTrait
 *
 * @package Fei\Service\Chat\Validator
 */
abstract class AbstractContextValidatorAware extends AbstractValidator
{
    /**
     * Validate contexts
     *
     * @param mixed $context
     *
     * @return bool
     */
    public function validateContext($context)
    {
        if (!$context instanceof Collection) {
            $this->addError(
                'contexts',
                'Context has to be an instance of \Doctrine\Common\Collections\Collection'
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
