<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use LogiCorpoBundle\Entity\Utilisateur;
use LogiCorpoBundle\Entity\Transaction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
		$form = $this->get('form.factory')->createBuilder('form', $user)
			->add('nom', 'text')
			->add('prenom', 'text', ['label' => 'Prénom'])
			->add('username', 'text', ['label' => 'Login'])
			->add('mail', 'email')
			->add('solde', 'money', [
				'label' => 'Solde initial',
				'data'  => 0
			])
			->add('password', 'password', ['label' => 'Mot de passe'])
			->add('rang', 'entity', [
				'class' => 'LogiCorpoBundle:Rang'])
			->add('Ajouter', 'submit')
			->getForm();

		$form->handleRequest($req);

		if($form->isValid()) {
			$this->saveUser($user);
			$em = $this->getDoctrine()->getManager();
			$em->flush();

			$req->getSession()->getFlashBag()->add('success','L\'utilisateur "'.$user.'" a été crée');
			
			return $this->redirect($this->generateUrl('lc_utilisateur_home'));
		}

		return $this->render('LogiCorpoBundle:Utilisateur:nouveau.html.twig', ['form' => $form->createView()]);
	}

	/*
	 * Sauvegarde un nouvel utilisateur valide
	 *
	 */
	private function saveUser(Utilisateur $user) {

		$em = $this->getDoctrine()->getManager();

		$encoder = $this->get('security.encoder_factory')->getEncoder($user);
		$mdp = $encoder->encodePassword(
			$user->getPassword(),
			$user->getSalt()
		);

		$user->setPassword($mdp);

		if($user->getSolde() > 0) {
			$transaction = new Transaction();
			$transaction->setType('mouvement_carte')
						->setMontant($user->getSolde())
						->setUtilisateur($user)
						->setMoyenPaiement('compte');
			$em->persist($transaction);
		}

		$em->persist($user);
	}

	public function nouveauListeAction(Request $req) {
		$max_size = 10; //Mo
		$form = $this->createFormBuilder()
			->add('fichier', 'file', [
				'label' => 'Fichier *.csv',
				'attr' => [
					'accept' => '.csv'
				]
			])
			->add('Ajouter', 'submit')
			->getForm();
		
		$form->handleRequest($req);

		if($form->isSubmitted()) {
			$fichier = $form->get('fichier')->getNormData();
			
			if($fichier->isValid()) {

				$point  = fopen($fichier->getPathName(),"r");
				$utilisateurs = $this->csv_to_array($point);
				/*
				* Vérifie que les champs nom, prenom et membre sont présent
				*/
				if(array_key_exists('nom',$utilisateurs[0]) && array_key_exists('prenom',$utilisateurs[0]) && array_key_exists('membre',$utilisateurs[0])) {
					/*
					* Recupère les rangs
					*/
					$em = $this->getDoctrine()->getManager();
					$repository = $em->getRepository('LogiCorpoBundle:Rang');
					$rang = [
						0 => $repository->findOneBySlug('NON_MEMBRE'),
						1 => $repository->findOneBySlug('MEMBRE')
					];

					$users = [];
					$i=0;
					foreach ($utilisateurs as $utilisateur) {
						$u = $users[$i++] = new Utilisateur();
						$u->setNom($utilisateur['nom']);
						$u->setPrenom($utilisateur['prenom']);
						$u->setRang($rang[$utilisateur['membre']]);

						/**
						* Si le solde initial est spécifié, on le renseigne, sinon on l'initialise à 0
						*/
						if(isset($utilisateur['solde']))
							$u->setSolde(floatval($utilisateur['solde']));
						else
							$u->setSolde(0);

						/**
						* Si le login est spécifié, on le renseigne, sinon on l'initialise à :
						* première lettre du prenom + 6 premières lettres du nom + "_"
						*/
						if(isset($utilisateur['login']))
							$login = $utilisateur['login'];
						else
							$login = preg_replace("/[^A-Z]+/", "",substr($u->getPrenom(),0,1))
								   . preg_replace("/[^A-Z]+/", "", substr($u->getNom(),0,6))
								   . "_";
						$u->setUsername(strtolower($login));

						/**
						* Si le mdp est spécifié, on le renseigne, sinon on l'initialise par une valeur aléatoire
						*/
						if(isset($utilisateur['mdp']))
							$u->setPassword($utilisateur['mdp']);
						else
							$u->setPassword('12345');//uniqid();
					}
					dump($users);
					// Recapitulatif

				} else {
					$req->getSession()->getFlashBag()->add('err',"Les champs nom, prenom et membre, doivent être dans le fichier.");
				}
			} else {
				$req->getSession()->getFlashBag()->add('err',"Le fichier n'est pas valide");
			}

			/*
				die();


				$fields = array_map('strtolower',fgetcsv($point));

				/*
				* Vérifie que les champs nom, prenom et membre sont présent
				*
				if(in_array('nom', $fields) && in_array('prenom', $fields) && in_array('membre', $fields)) {
					$fields = array_flip($fields);

					$em = $this->getDoctrine()->getManager();

					$repository = $em->getRepository('LogiCorpoBundle:Rang');
					$rang = [
						0 => $repository->findOneBySlug('NON_MEMBRE'),
						1 => $repository->findOneBySlug('MEMBRE')
					];
					$resume = [];
					// Pour chaque lignes du fichiers
					while($ligne = fgetcsv($point)) {
						$user = new Utilisateur();

						$key_rang = intval($ligne[$fields['membre']]);
						
						$user->setNom($ligne[$fields['nom']])
							 ->setPrenom($ligne[$fields['prenom']])
							 ->setRang($rang[$key_rang]);

						/**
						* Si le solde initial est spécifié, on le renseigne, sinon on l'initialise à 0
						*
						if(isset($fields['solde']))	 $user->setSolde(floatval($ligne[$fields['solde']]));
						else                         $user->setSolde(0);

						/**
						* Si le login est spécifié, on le renseigne, sinon on l'initialise à :
						* première lettre du prenom + 6 premières lettres du nom + "_"
						*
						if(isset($fields['login']))  $login = $ligne[$fields['login']];
						else                         $login = substr($user->getPrenom(),0,1) . substr($user->getNom(),0,6) . "_";
						$user->setUsername(strtolower($login));

						/**
						* Si le mdp est spécifié, on le renseigne, sinon on l'initialise par une valeur aléatoire
						*
						if(isset($fields['mdp'])) $mdp = $ligne[$fields['mdp']];
						else                      $mdp = '12345';//uniqid();

						$factory = $this->get('security.encoder_factory');
						$encoder = $factory->getEncoder($user);
						$encodeMdp = $encoder->encodePassword(
							$mdp,
							$user->getSalt()
						);

						$user->setPassword($encodeMdp);

						$resume[]['name'] = $user;
						$resume[]['login'] = $user->getUsername();
						$resume[]['mdp'] = $mdp;

						$em->persist($user);
					}
					fclose($point);
					try {
						dump($em->flush());
						$req->getSession()->getFlashBag()->add('success',"62 utilisateurs ont été créés");
					} catch (\Exception $e) {
						dump($e);
						if(isset($e->previous)) {
							dump($e->previous->code);
						}
						$req->getSession()->getFlashBag()->add('err',"Erreur sql");
					}
					

				} else {
					$req->getSession()->getFlashBag()->add('err',"Les champs nom, prenom et membre, doivent être dans le fichier.");
				}

			} else {
				$req->getSession()->getFlashBag()->add('err',"Le fichier n'est pas valide");
			}
			*/
		}
		return $this->render('LogiCorpoBundle:Utilisateur:nouveaux.html.twig', ['form' => $form->createView(), 'max_size'=> $max_size]);
	}

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
		$form = $this->get('form.factory')->createBuilder('form', $user)
			->add('nom', 'text')
			->add('prenom', 'text', ['label' => 'Prénom'])
			->add('username', 'text', ['label' => 'Login'])
			->add('mail', 'email')
			->add('rang', 'entity', [
				'class' => 'LogiCorpoBundle:Rang'])
			->add('Enregistrer', 'submit')
			->add('Supprimer', 'submit', [
				'attr' => ['class' => 'btn-rouge']
			])
			->getForm();

		$form->handleRequest($req);

		if($form->isValid()) {
			if($form->get('Supprimer')->isClicked()) {
				$this->redirect($this->generateUrl('lc_utilisateur_suppr', ['user' => $user->getId()]));
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
			
			return $this->redirect($this->generateUrl('lc_utilisateur_home'));
		}

		return $this->render('LogiCorpoBundle:Utilisateur:editer.html.twig', ['form' => $form->createView()]);
	}

	public function supprimerAction($id, Utilisateur $user, Request $req) {
		$em = $this->getDoctrine()->getManager();
		$em->remove($user);
		$em->flush();
		return $this->redirect($this->generateUrl('lc_utilisateur_home'));
	}

	public function soldeAction($id, Utilisateur $user, Request $req)
	{
		$transaction = new Transaction();
		$transaction->setUtilisateur($user)
					->setMoyenPaiement('espece');
		$form = $this->get('form.factory')->createBuilder('form', $transaction)
			->add('type','choice',[
				'choices' => [
					'mouvement_carte' => 'Approvisionement de compte',
					'remboursement'   => 'Remboursement'
				],
				'preferred_choices' => 'mouvement_carte',
				'label' => 'Motif'
			])
			->add('montant','money')
			->add('submit','submit', [
				'label' => 'Débiter/Créditer'
			])
			->getForm();

		$form->handleRequest($req);
		
		if($form->isValid()) {

			$user->appendSolde($transaction->getMontant());

			$em = $this->getDoctrine()->getManager();
			$em->persist($transaction);
			$em->persist($user);
			$em->flush();

			$req->getSession()->getFlashBag()->add('success','La transaction pour "'.$user.'" a été enregistrée');
			return $this->redirect($this->generateUrl('lc_utilisateur_home'));
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

			$transaction = new Transaction();
			$transaction->setType('frais_adhesion')
						->setMoyenPaiement($moyenPaiement)
						->setMontant($montantAdhesion)
						->setUtilisateur($user);
			$em->persist($transaction);
			$em->flush();
		
			$req->getSession()->getFlashBag()->add('success', "L'utilisateur $user est maintenant un membre.");
		} else {
			$req->getSession()->getFlashBag()->add('err', "Impossible de passer l'utilisateur $user au rang membre.");
		}
		
		return $this->redirectToRoute($redirection);
	}
}
