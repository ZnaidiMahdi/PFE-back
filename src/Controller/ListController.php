<?php

namespace App\Controller;

use App\Repository\TestRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractFOSRestController
{
    /**
     * @Route("/api/list", name="app_lists")
     * @return View
     */
    public function getListAction(TestRepository $testRepository)
    {
        $data = $testRepository->findAll();
        return $this->view($data, Response::HTTP_OK);
    }
}
