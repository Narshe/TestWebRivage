<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LanguageExpression extends Constraint
{
    /**
     * @var $entity Permet de choisir l'entité sur laquelle on veut appliquer la règle
     */
    public $entity;
    public $message = 'The value "{{ value }}" is not valid.';


}

