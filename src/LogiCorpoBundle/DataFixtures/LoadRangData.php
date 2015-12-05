<?php 
namespace LogiCorpoBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use LogiCorpoBundle\Entity\Rang;

class LoadRangData extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$rangs = array(
			array('NON_MEMBRE', 'Non-membre', false, 'E'),
			array('MEMBRE', 'Membre', true, 'E'),
			array('PARTICIPANT', 'Participant', true, 'E'),
			array('TRESORIER', 'Trésorier', true, 'E'),
			array('PRESIDENT', 'Président', true, 'E'),
		);

		foreach ($rangs as list($slug, $nom, $reduc, $typeReduc)) {
			$this->newRang($manager, $slug, $nom, $reduc, $typeReduc);
		}
		$manager->flush();
	}
 
	private function newRang(ObjectManager $manager, $slug, $nom, $reduc, $typeReduc)
	{
		$rang = new Rang();
		$rang->setSlug($slug)
		     ->setNom($nom)
		     ->setReduction($reduc)
		     ->setTypeReduc($typeReduc);

		$manager->persist($rang);
		$this->setReference($slug);
	}

	public function getOrder()
	{
		return 1;
	}
}