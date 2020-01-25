<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class PageController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="home")
     */
    public function index()
    {   
        return $this->render('pages/home.html.twig');
    }

}
