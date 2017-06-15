<?php

namespace Tests\Fei\Service\Chat\Validator;

use Doctrine\Common\Collections\ArrayCollection;
use Fei\Entity\EntityInterface;
use Fei\Service\Chat\Entity\RoomContext;
use Fei\Service\Chat\Validator\AbstractContextValidatorAware;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractContextValidatorAwareTest
 *
 * @package Tests\Fei\Service\Chat\Validator
 */
class AbstractContextValidatorAwareTest extends TestCase
{
    /**
     * @dataProvider dataForTestValidateContext
     *
     * @param $collection
     * @param $validation
     * @param $error
     */
    public function testValidateContext($collection, $validation, $error)
    {
        $validator = new class extends AbstractContextValidatorAware {
            public function validate(EntityInterface $entity)
            {
            }
        };

        $result = $validator->validateContext($collection);

        $this->assertEquals($validation, $result);

        if (!$result) {
            $this->assertEquals($error, $validator->getErrors()['contexts'][0]);
        }
    }

    public function dataForTestValidateContext()
    {
        return [
            [new RoomContext(), false, 'Context has to be an instance of \Doctrine\Common\Collections\Collection'],
            [new ArrayCollection(), true, null],
            [new ArrayCollection([new RoomContext()]), false, 'key: The key cannot be empty; value: The value cannot be empty'],
            [
                new ArrayCollection([(new RoomContext())->setKey('test')->setValue('test')]),
                true,
                null
            ]
        ];
    }
}
