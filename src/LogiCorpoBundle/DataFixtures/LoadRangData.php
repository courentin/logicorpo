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
			array('NON_MEMBRE', 'Non-membre', 0.00, '€'),
			array('MEMBRE', 'Membre', 0.05, '€'),
			array('PARTICIPANT', 'Participant', 0.05, '€'),
			array('TRESORIER', 'Trésorier', 0.05, '€'),
			array('PRESIDENT', 'Président', 0.05, '€'),
		);

		foreach ($rangs as list($slug, $nom, $reduc, $typeReduc)) {
			$rang = new Rang();
			$rang->setSlug($slug)
			     ->setNom($nom)
			     ->setReduction($reduc)
			     ->setTypeReduc($typeReduc);

			$manager->persist($rang);
			$this->setReference($slug, $rang);
		}
		$manager->flush();
	}

	public function getOrder()
	{
		return 1;
	}
}