<?php 
namespace SettingsBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Exception\InvalidPropertyException;
use Doctrine\DBAL\Types\Type;

class SettingsService
{
	private $em;
	private $settingsRep;
	private $settings;

	public function __construct(EntityManager $em) {
		$this->em = $em;
		$this->settingsRep = $em->getRepository('SettingsBundle:Setting');
	}

	public function __get($label) {
		if(!isset($this->settings[$label])) {
			$setting = $this->settingsRep->findOneByLabel($label);
			if(!$setting) return null;
			else $this->settings[$label] = $setting;
		}

		return Type::getType($this->settings[$label]->getType())
				   ->convertToPHPValue($this->settings[$label]->getValue(), $this->em->getConnection()->getDatabasePlatform());
	}


	public function __set($label, $value) {
		$setting = $this->settingsRep->findOneByLabel($label);
		if(!$setting) return false;

		$setting->setValue($value);
		$this->em->flush();
		return $this;
	}

	public function __isset($label) {
		return $this->settingsRep->findOneByLabel($label)!=null;
	}
}