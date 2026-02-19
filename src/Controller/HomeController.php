<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {
	#[Route('/', name: 'index')]
	public function index(): Response {
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		return $this->render("home/home.html.twig");
	}
}

?>