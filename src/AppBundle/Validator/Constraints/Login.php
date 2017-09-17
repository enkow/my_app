<?php

/**
 * Login constraint.
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Login
 *
 * @Annotation
 * @package AppBundle\Validator\Constraints
 */
class Login extends Constraint
{
    /**
     * Constraint message
     *
     * @var string $message
     */
    public $message = "message.use.wierzba.login";

    public function validatedBy()
    {
        return LoginValidator::class;
    }
}
