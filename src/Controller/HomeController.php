<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {
	#[Route('/', name: 'app_home')]
	public function index(): Response {
		return new Response (
			"<html><body><p>Bienvenue sur ma page d'accueil !</p></body></html>"
		);
	}
}

?>