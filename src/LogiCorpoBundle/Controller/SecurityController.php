<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use LogiCorpoBundle\Entity\Utilisateur;

class SecurityController extends Controller
{
	public function loginAction(Request $request)
	{
		// Si le visiteur est déjà identifié, on le redirige vers l'accueil
		if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
		  return $this->redirect($this->generateUrl('lc_homepage'));
		}

		$session = $request->getSession();

		// On vérifie s'il y a des erreurs d'une précédente soumission du formulaire
		if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
		  $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
		} else {
		  $error = $session->get(Security::AUTHENTICATION_ERROR);
		  $session->remove(Security::AUTHENTICATION_ERROR);
		}

		return $this->render('LogiCorpoBundle:Login:login.html.twig', array(
		  // Valeur du précédent nom d'utilisateur entré par l'internaute
		  'last_username' => $session->get(Security::LAST_USERNAME),
		  'error'         => $error,
		));
	}

	public function changePassAction(Request $req) {
		$user = $this->getUser();

		$form = $this->get('form.factory')->createBuilder()
			->add('old_password', 'password', ['label' => 'Mot de passe actuel'])
			->add('new_password', 'repeated', [
				'type' => 'password',
				'first_options'  => ['label' => 'Nouveau mot de passe'],
				'second_options' => ['label' => 'Confirmation']
			])
			->add('Enregistrer', 'submit')
			->getForm()
		;

		$form->handleRequest($req);

		if($form->isValid()) {

			$em = $this->getDoctrine()->getManager();

			$factory = $this->get('security.encoder_factory');
			$encoder = $factory->getEncoder($user);

			$old_mdp = $encoder->encodePassword(
				$form->get('old_password')->getData(),
				$user->getSalt()
			);
			// verifier mdp act
			if($user->getPassword()!=$old_mdp) {
				$req->getSession()->getFlashBag()->add('err','Le mot de passe actuel est incorrect');
				return $this->render('LogiCorpoBundle:Compte:mdp.html.twig', ['form' => $form->createView()]);
			}

			$new_mdp = $encoder->encodePassword(
				$form->get('new_password')->getData(),
				$user->getSalt()
			);

			// enregistrer mdp
			$user->setPassword($new_mdp);
			$em->persist($user);
			$em->flush();

			$req->getSession()->getFlashBag()->add('success','Le mot de passe a été modifié');
			$this->redirect($this->generateUrl('lc_compte_home'));
				return $this->redirect($this->generateUrl('lc_compte_home'));

		}

		return $this->render('LogiCorpoBundle:Compte:mdp.html.twig', ['form' => $form->createView()]);
	}
}
