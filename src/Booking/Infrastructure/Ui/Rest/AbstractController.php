<?php

namespace Kata\Booking\Infrastructure\Ui\Rest;

use Kata\Common\Infrastructure\Bus\BusComponent;
use Symfony\Component\Validator\ConstraintViolation;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use function Lambdish\Phunctional\map;

abstract class AbstractController
{
    public function __construct(
        protected BusComponent $busComponent,
        protected ValidatorInterface $validator
    ) {
    }

    protected function guardHasError($dto): ?array
    {
        $errors = $this->validator->validate($dto);

        $errorMessage = [];

        if (count($errors) > 0) {
            $errorMessage['errors'] = map(fn(ConstraintViolation $error) => [
                'field' => substr($error->getPropertyPath(), 8, -1),
                'message' => $error->getMessage(),
            ], $errors);
            foreach ($errors as $violation) {
                /* @var ConstraintViolation $violation */
                $messages[$violation->getPropertyPath()] = $violation->getMessage();
            }

        }
        return $errorMessage;
    }
}