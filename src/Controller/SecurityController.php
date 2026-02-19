<?php
namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController {
	#[Route('/login', name: 'login')]
	public function login(AuthenticationUtils $utils) {
		$error = $utils->getLastAuthenticationError();
		$lastUsername = $utils->getLastUsername();

		return $this->render("security/login.html.twig", [
			"last_username" => $lastUsername,
			"error" => $error
		]);
	}

	#[Route('/logout', name: 'logout')]
    public function logout()
    {
		   
    }
}

?>