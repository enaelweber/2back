<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController {
	#[Route('/hello/{name}', name: 'app_hello')]
	public function hello(string $name): Response {
		return $this->render("home/hello.html.twig", ["name" => ucfirst($name)]);
	}
}

?>