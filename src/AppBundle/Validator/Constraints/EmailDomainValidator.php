<?php

/**
 * Email Domain Validator.
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class EmailDomainValidator
 *
 * @package AppBundle\Validator\Constraints
 */
class EmailDomainValidator extends ConstraintValidator
{
    /**
     * Validate.
     *
     * @param mixed      $entity
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $mail = explode('@', $value);

        if (!in_array($mail[1], $constraint->domains)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ email }}', $value)
                ->addViolation();
        }
    }
}
