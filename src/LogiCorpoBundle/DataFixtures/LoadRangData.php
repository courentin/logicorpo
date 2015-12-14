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
			array('NON_MEMBRE', 'Non-membre', false, '€'),
			array('MEMBRE', 'Membre', true, '€'),
			array('PARTICIPANT', 'Participant', true, '€'),
			array('TRESORIER', 'Trésorier', true, '€'),
			array('PRESIDENT', 'Président', true, '€'),
		);

		foreach ($rangs as list($slug, $nom, $reduc, $typeReduc)) {
			$rang = new Rang();
			$rang->setSlug($slug)
			     ->setNom($nom)
			     ->setReduction($reduc)
			     ->setTypeReduc($typeReduc);

			$manager->persist($rang);
			$this->setReference($slug);
		}
		$manager->flush();
	}

	public function getOrder()
	{
		return 1;
	}
}