<?php

namespace App\Controller;

use App\Entity\DiscountRule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\DiscountRuleType;

use App\Repository\DiscountRuleRepository;

class DiscountRuleController extends AbstractController
{
    /**
     * @Route("/discount/rule", methods={"GET"}, name="discount_rule_index")
     */
    public function index(DiscountRuleRepository $discountRepository)
    {   

        $discountRules = $discountRepository->findAll();

        return $this->render('discount_rule/index.html.twig', [
            'discount_rules' => $discountRules,
        ]);
    }

    
    /**
     * @Route("/discount/rule/new", methods={"GET", "POST"}, name="discount_rule_new")
     */
    public function new(Request $request)
    {
        $discountRule = new DiscountRule();

        $form = $this->createForm(DiscountRuleType::class, $discountRule);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $em = $this->getDoctrine()->getManager();
            $em->persist($discountRule);

            $em->flush();

            return $this->redirectToRoute('discount_rule_index');

        }

        return $this->render('discount_rule/new.html.twig', [
            'discount_form' => $form->createView()
        ]);
    }
}
