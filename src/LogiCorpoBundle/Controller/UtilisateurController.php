<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use LogiCorpoBundle\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Security("has_role('ROLE_PARTICIPANT')")
 */
class UtilisateurController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repository = $em->getRepository('LogiCorpoBundle:Utilisateur');
    	$utilisateurs = $repository->findAll();

        return $this->render('LogiCorpoBundle:Utilisateur:index.html.twig', ['utilisateurs' => $utilisateurs]);
    }

    public function nouveauAction(Request $req) {
    	$user = new Utilisateur();
    	$form = $this->get('form.factory')->createBuilder('form', $user)
    		->add('nom', 'text')
    		->add('prenom', 'text', ['label' => 'Prénom'])
            ->add('username', 'text', ['label' => 'Login'])
    		->add('solde', 'text', ['label' => 'Solde initial'])
    		->add('password', 'password', ['label' => 'Mot de passe'])
            ->add('rang', 'entity', [
                'class' => 'LogiCorpoBundle:Rang'])
    		->add('Ajouter', 'submit')
    		->getForm()
    	;

    	$form->handleRequest($req);

    	if($form->isValid()) {
    		$em = $this->getDoctrine()->getManager();

    		$factory = $this->get('security.encoder_factory');
    		$encoder = $factory->getEncoder($user);
    		$mdp = $encoder->encodePassword(
    			$user->getPassword(),
    			$user->getSalt()
    		);

    		$user->setPassword($mdp);

    		$em->persist($user);
    		$em->flush();
	    	$req->getSession()->getFlashBag()->add('success','L\'utilisateur "'.$user.'" a été crée');
            
            return $this->redirect($this->generateUrl('lc_utilisateur_home'));
    	}

        return $this->render('LogiCorpoBundle:Utilisateur:nouveau.html.twig', ['form' => $form->createView()]);
    }

    public function modifierAction($id, Utilisateur $user, Request $req) {
        $form = $this->get('form.factory')->createBuilder('form', $user)
            ->add('nom', 'text')
            ->add('prenom', 'text', ['label' => 'Prénom'])
            ->add('username', 'text', ['label' => 'Login'])
            ->add('solde', 'money', ['label' => 'Solde initial'])
            ->add('password', 'password', ['label' => 'Mot de passe'])
            ->add('rang', 'entity', [
                'class' => 'LogiCorpoBundle:Rang'])
            ->add('Ajouter', 'submit')
            ->getForm()
        ;

        $form->handleRequest($req);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $mdp = $encoder->encodePassword(
                $user->getPassword(),
                $user->getSalt()
            );

            $user->setPassword($mdp);

            $em->persist($user);
            $em->flush();
            $req->getSession()->getFlashBag()->add('success','L\'utilisateur "'.$user.'" a été crée');
            
            return $this->redirect($this->generateUrl('lc_utilisateur_home'));
        }

        return $this->render('LogiCorpoBundle:Utilisateur:nouveau.html.twig', ['form' => $form->createView()]);
    }
}
