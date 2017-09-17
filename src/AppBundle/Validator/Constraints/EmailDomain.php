<?php

/**
 * Email domain constraint.
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class EmailDomain
 *
 * @Annotation
 * @package AppBundle\Validator\Constraints
 */
class EmailDomain extends Constraint
{
    /**
     * Constraint message
     *
     * @var string $message
     */
    public $message = "message.domain.not.allowed";

    public $domains = ['uj.edu.pl', 'student.uj.edu.pl'];

    public function validatedBy()
    {
        return EmailDomainValidator::class;
    }
}
