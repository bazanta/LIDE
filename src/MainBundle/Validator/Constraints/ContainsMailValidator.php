<?php

namespace MainBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsMailValidator extends ConstraintValidator
{
    private $suffixe;
    private $suffixeRegex;
    
    public function __construct($suffixes)
    {
        $this->suffixeRegex = "/^(.*)@(";
        $this->suffixe = $suffixes;
        foreach ($suffixes as $key => $suf) {
            if ($key != 0) {
                $this->suffixeRegex .= "|";
            }
            $this->suffixeRegex .= '('.$suf.')';            
        }
        $this->suffixeRegex .= ")$/";
    }

    public function validate($value, Constraint $constraint)
    {
        if (!preg_match($this->suffixeRegex, $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ suffixe }}', implode(" ou ", $this->suffixe))
                ->addViolation();
        }
    }
}
?>