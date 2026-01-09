<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RandomController extends AbstractController {
	#[Route('/random', name: 'app_random')]
	public function random(): Response {
		$quotes = [
			'Le code est poésie.',
			'Symfony simplifie la complexité.',
			'Toujours tester, jamais supposer.',
			'Refactoriser, c’est aimer son futur soi.'
		];
		
		$quote = $quotes[random_int(0,3)];
		return $this->render("home/random.html.twig", ["quote" => $quote]);
	}
}

?>