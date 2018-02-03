<?php

namespace MainBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsMail extends Constraint
{
    public $message = 'Email invalide, email comme "{{ suffixe }}"';
};

?>