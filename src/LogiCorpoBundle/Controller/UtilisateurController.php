<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use LogiCorpoBundle\Entity\Utilisateur;
use LogiCorpoBundle\Entity\Transaction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use LogiCorpoBundle\Form\UtilisateurType;
/**
 * @Security("has_role('ROLE_PARTICIPANT')")
 */
class UtilisateurController extends Controller
{
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('LogiCorpoBundle:Utilisateur');
		$utilisateurs = $repository->findBy(array(), array('nom'=>'asc'));

		return $this->render('LogiCorpoBundle:Utilisateur:index.html.twig', ['utilisateurs' => $utilisateurs]);
	}

	public function jsonListAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('LogiCorpoBundle:Utilisateur');
		$utilisateurs = $repository->findAll();
		$response = new JsonResponse();
		$response->setData($utilisateurs);

		return $response;
	}

	public function nouveauAction(Request $req) {
		$user = new Utilisateur();

		$form = $this->createForm(UtilisateurType::class, $user)
		             ->add('Ajouter', SubmitType::class);

		$form->handleRequest($req);

		if($form->isValid()) {
			$this->saveUser($user);
			$em = $this->getDoctrine()->getManager();
			$em->flush();

			$req->getSession()->getFlashBag()->add('success','L\'utilisateur "'.$user.'" a été crée');
			
			return $this->redirectToRoute('lc_utilisateur_home');
		}

		return $this->render('LogiCorpoBundle:Utilisateur:nouveau.html.twig', ['form' => $form->createView()]);
	}


	public function nouveauListeAction(Request $req, $max_size = 10) {
		/*
		 * Formulaire
		 */
		$form = $this->createFormBuilder()
			->add('fichier', FileType::class, [
				'label' => 'Fichier *.csv',
				'attr' => [
					'accept' => '.csv'
				]
			])
			->add('Importer', SubmitType::class)
			->getForm();
		
		$form->handleRequest($req);

		if($form->isSubmitted()) {
			$fichier = $form->get('fichier')->getNormData();
			
			if($fichier->isValid()) {

				$point  = fopen($fichier->getPathName(),"r");
				$utilisateurs = $this->csv_to_array($point);
				
				$requiredFields = ['nom', 'prenom', 'membre', 'email'];
				
				/*
				* Vérifie que les champs nom, prenom et membre sont présent
				*/
				$hasRequiredFields = true;
				foreach ($requiredFields as $field) {
					if(!array_key_exists($field, $utilisateurs[0])) ;
				}

				if($hasRequiredFields) {
					$em = $this->getDoctrine()->getManager();

					/*
					* Recupère les rangs
					*/
					$rang = [
						0 => $em->getReference('LogiCorpoBundle:Rang','NON_MEMBRE'),
						1 => $em->getReference('LogiCorpoBundle:Rang','MEMBRE')
					];

					$users = [];
					foreach ($utilisateurs as $utilisateur) {
						$u = $users[] = new Utilisateur();
						$u->setNom($utilisateur['nom'])
						  ->setPrenom($utilisateur['prenom'])
						  ->setMail($utilisateur['email'])
						  ->setRang($rang[$utilisateur['membre']]);

						// Si le solde initial est spécifié, on le renseigne, sinon on l'initialise à 0
						if(isset($utilisateur['solde']))
							$u->setSolde(floatval($utilisateur['solde']));

						// Si le login est spécifié, on le renseigne
						if(isset($utilisateur['login']))
							$u->setUsername(strtolower($utilisateur['login']));

						// envoyer mail

						$this->saveUser($u);
					}
					dump($users);
					$em->flush();
					// Recapitulatif

				} else {
					$req->getSession()->getFlashBag()->add('err',"Les champs ".implode(', ', $requiredFields).", doivent être dans le fichier.");
				}

			} else {
				$req->getSession()->getFlashBag()->add('err',"Le fichier n'est pas valide");
			}
		}
		return $this->render('LogiCorpoBundle:Utilisateur:nouveaux.html.twig', ['form' => $form->createView(), 'max_size'=> $max_size]);
	}

	/*
	 * Persiste un nouvel utilisateur valide dans l'entity manager
	 */
	private function saveUser(Utilisateur $user) {

		$this->sendInscriptionMail($user);

		$em = $this->getDoctrine()->getManager();

		$encoder = $this->get('security.encoder_factory')->getEncoder($user);
		$mdp = $encoder->encodePassword(
			$user->getPassword(),
			$user->getSalt()
		);

		$user->setPassword($mdp);

		if($user->getSolde() !== 0) {
			$transaction = new Transaction\TransactionCompte();
			$transaction->setUtilisateur($user)
						->setMontant(-$user->getSolde())
						->setCaissier($this->getUser());

			$em->persist($transaction);
		}

		$em->persist($user);
	}

	private function sendInscriptionMail(Utilisateur $user){
		if($user->getLastLog() == null) {		
			$message = \Swift_Message::newInstance()
				->setSubject($this->get('settings_manager')->get('mail_inscription_objet'))
				->setFrom("noreply@logiCorpo.fr")
				->setTo($user->getMail())
				->setBody(
					$this->renderView('LogiCorpoBundle:Mail:inscription.twig.html', ['utilisateur' => $user]),
					'text/html'
				);

			$this->get('mailer')->send($message);
		}
	}

	/*
	 * Permet de convertir un fichier csv en tableau
	 */
	private function csv_to_array($handle, $delimiter=',')
	{
	    $header = NULL;
	    $data = array();

        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine(array_map('strtolower', $header), $row);
        }
        fclose($handle);

	    return $data;
	}

	public function modifierAction($id, Utilisateur $user, Request $req) {
		$form = $this->createForm(UtilisateurType::class, $user, [ 'solde' => false ])
					 ->add('Enregistrer', SubmitType::class)
					 ->add('Supprimer', SubmitType::class, [
					 	 'attr' => ['class' => 'btn-rouge']
					 ]);

		$form->handleRequest($req);

		if($form->isValid()) {
			if($form->get('Supprimer')->isClicked()) {
				$this->redirectToRoute('lc_utilisateur_suppr', ['user' => $user->getId()]);
			}

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
			$req->getSession()->getFlashBag()->add('success','L\'utilisateur "'.$user.'" a été modifié');
			
			return $this->redirectToRoute('lc_utilisateur_home');
		}

		return $this->render('LogiCorpoBundle:Utilisateur:editer.html.twig', ['form' => $form->createView()]);
	}

	public function supprimerAction($id, Utilisateur $user, Request $req) {
		$em = $this->getDoctrine()->getManager();
		$em->remove($user);
		$em->flush();
		return $this->redirectToRoute('lc_utilisateur_home');
	}

	public function soldeAction($id, Utilisateur $user, Request $req)
	{
		$form = $this->createFormBuilder()
			->add('type', ChoiceType::class,[
				'choices' => [
					'mouvement_carte' => 'Approvisionement de compte',
					'remboursement'   => 'Remboursement'
				],
				'choices_as_values' => true,
				'preferred_choices' => 'mouvement_carte',
				'label' => 'Motif'
			])
			->add('montant', MoneyType::class)
			->add('submit', SubmitType::class, [
				'label' => 'Débiter/Créditer'
			])
			->getForm();

		$form->handleRequest($req);
		
		if($form->isValid()) {
			$type = $form->get('type')->getNormData();

			if($type == "mouvement_carte")
				$transaction = new Transaction\TransactionCompte();
			else
				$transaction = new Transaction\TransactionRemboursement();

			$transaction->setUtilisateur($user)
						->setCaissier($this->getUser())
						->setMontant(-$form->get('montant')->getData());

			$em = $this->getDoctrine()->getManager();
			$em->persist($transaction);
			$em->flush();

			$req->getSession()->getFlashBag()->add('success','La transaction pour "'.$user.'" a été enregistrée');
			return $this->redirectToRoute('lc_utilisateur_home');
		}

		return $this->render('LogiCorpoBundle:Utilisateur:solde.html.twig', ['form' => $form->createView(), 'user' => $user]);
	}

	/**
	 * Permet de passer un utilisateur du rang non membre au rang membre
	 * @Security("has_role('ROLE_NON_MEMBRE')")
	 */
	public function upgradeAction(Utilisateur $user = null, $redirection, $moyenPaiement, Request $req) {
		// Vérification des autorisations
		if ($user == null) {
			if(!$this->get('security.authorization_checker')->isGranted('ROLE_MEMBRE')) {
				$user = $this->getUser();
			} else {
				throw new AccessDeniedException();
			}
		} else if(!$this->get('security.authorization_checker')->isGranted('ROLE_PARTICIPANT')) {
			throw new AccessDeniedException();
		}

		// Passer au rang membre
		$config = $this->get('settings_manager');
		$montantAdhesion = $config->get('montantAdhesion');
		$em = $this->getDoctrine()->getManager();

		if($user->canPay($montantAdhesion, $config->get('seuil')) && $user->getRang()->getSlug() == 'NON_MEMBRE') {
			$user->subSolde($montantAdhesion)
				 ->setRang($em->getReference('LogiCorpoBundle:Rang','MEMBRE'));

			$transaction = new Transaction\TransactionFraisAdhesion();
			$transaction->setMoyenPaiement($moyenPaiement)
						->setMontant($montantAdhesion)
						->setUtilisateur($user)
						->setCaissier($this->getUser());
			$em->persist($transaction);
			$em->flush();
		
			$req->getSession()->getFlashBag()->add('success', "L'utilisateur $user est maintenant un membre.");
		} else {
			$req->getSession()->getFlashBag()->add('err', "Impossible de passer l'utilisateur $user au rang membre.");
		}
		
		return $this->redirectToRoute($redirection);
	}
}
