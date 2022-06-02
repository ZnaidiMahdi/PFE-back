<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionReponseController extends AbstractController
{
    /**
     * @Route("/question/reponse", name="app_question_reponse")
     */
    public function index(): Response
    {
        return $this->render('question_reponse/index.html.twig', [
            'controller_name' => 'QuestionReponseController',
        ]);
    }
}
