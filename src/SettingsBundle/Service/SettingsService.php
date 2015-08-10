<?php 
namespace SettingsBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Exception\InvalidPropertyException;
use SettingsBundle\Entity\Setting;

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
		return $this->settings[$label]->getValue();
	}


	public function __set($label, $value) {
		$setting = $this->settingsRep->findOneByLabel($label);
		if(!$setting) return false;

		$setting->setValue($value);
		$this->em->flush();
		return $value;
	}

	public function __isset($label) {
		return $this->settingsRep->findOneByLabel($label)!=null;
	}
}