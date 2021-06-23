<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateDBController extends AbstractController
{
    public function showCreateBtns() : Response
    {
		return $this->render('create.html.twig');
    }

	public function createOrderDB(): Response
    {
        $response = $this->forward('App\Controller\OrderController::createOrderDB');
	
		return $response;
    }

	public function createProductDB(Request $request): Response
    {
		$response = $this->forward('App\Controller\ProductController::createProductDB');
	
		return $response;
    }
}