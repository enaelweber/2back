<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {
	#[Route('/', name: 'index')]
	public function index(): Response {
		return $this->render("home/home.html.twig");
	}

	#[Route('/random', name: 'random')]
	public function random(): Response {
		$quotes = [
			'Le code est poésie.',
			'Symfony simplifie la complexité.',
			'Toujours tester, jamais supposer.',
			'Refactoriser, c’est aimer son futur soi.'
		];
		
		$quote = $quotes[random_int(0, sizeof($quotes)-1)];
		return $this->render("home/random.html.twig", ["quote" => $quote]);
	}

	#[Route('/about', name: 'about')]
	public function about(): Response {
		return $this->render("home/about.html.twig");
	}

	#[Route('/hello/{name}', name: 'hello')]
	public function hello(string $name): Response {
	}
}

?>