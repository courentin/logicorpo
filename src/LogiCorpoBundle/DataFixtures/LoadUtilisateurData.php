<?php 
namespace LogiCorpoBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadUtilisateurData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$utilisateur = new utilisateur();
		$utilisateur
			->setUsername('admin')
			->setNom('')
			->setPrenom('Administrator')
			->setRang($this->getReference('PRESIDENT'));

		$manager->persist($utilisateur);
		$manager->flush();
	}

	public function getOrder()
	{
		return 2;
	}
}