<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use App\Validator\LanguageExpression;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DiscountRuleRepository")
 * @UniqueEntity("rule_expression")
 */
class DiscountRule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @LanguageExpression(
     * entity = "App\Entity\Product",
     * message="La valeur de l'expression {{ value }} n'est pas valide, se référer à https://symfony.com/doc/current/components/expression_language/syntax.html pour plus d'information"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $rule_expression;

    /**
     * @Assert\NotBlank
     * @Assert\Type("integer")
     * @Assert\Range(
     *  min=1,
     *  max=50,
     *  minMessage = "Vous devez entrer un entier entre 1 et 50",
     *  maxMessage = "Vous devez entrer un entier entre 1 et 50"
     * )
     * @ORM\Column(type="integer")
     */
    private $discount_percent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRuleExpression(): ?string
    {
        return $this->rule_expression;
    }

    public function setRuleExpression(string $rule_expression): self
    {
        $this->rule_expression = $rule_expression;

        return $this;
    }

    public function getDiscountPercent(): ?int
    {
        return $this->discount_percent;
    }

    public function setDiscountPercent(int $discount_percent): self
    {
        $this->discount_percent = $discount_percent;

        return $this;
    }
}
