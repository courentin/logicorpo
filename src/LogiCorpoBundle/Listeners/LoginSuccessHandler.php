<?php

namespace LogiCorpoBundle\Listeners;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManager;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
	
	protected $router;
	protected $em;
	
	public function __construct(EntityManager $em, Router $router)
	{
		$this->em = $em;
		$this->router = $router;
	}
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token)
	{
		$user = $token->getUser();

		if(null === $user->getLastLog()) {
			$response = new RedirectResponse($this->router->generate('lc_compte_mdp'));
		} else {
			$response = new RedirectResponse($this->router->generate('lc_homepage'));
		}
		$user->setLastLog(new \DateTime());
		$this->em->flush();
	
		return $response;
	}
	
}