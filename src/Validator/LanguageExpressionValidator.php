<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\ExpressionLanguage\SyntaxError;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;


class LanguageExpressionValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\LanguageExpression */

        if (null === $value || '' === $value) {
            return;
        }

        try {
            $entity = new $constraint->entity();

            $exprLanguage = new ExpressionLanguage();
            $exprLanguage->evaluate($value, [$this->getFormatedName($constraint->entity) => $entity]);
        }
        catch(SyntaxError $e) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
        }

    }
    
    /**
     * @param string $fullEntityName
     * @return string
     */
    private function getFormatedName(string $fullEntityName): string {

        $parts = explode('\\',$fullEntityName);
        return strtolower(end($parts));
    }

}
