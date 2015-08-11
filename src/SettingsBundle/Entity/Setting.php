<?php 
namespace SettingsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SettingsBundle\Entity\Setting;

/**
 * Setting
 * @ORM\Table(name="setting")
 * @ORM\Entity
 */
class Setting
{

	/**
	 * @var string
     * @ORM\Column(name="label", type="text", nullable=false)
     * @ORM\Id
	 */
	private $label;

	/**
	 * @var string
	 * @ORM\Column(name="value", type="text")
	 */
	private $value;

	/**
	 * @var string
	 * @ORM\Column(name="type", type="text", nullable=false)
	 */
	private $type;

	public function getLabel() {
		return $this->label;
	}

	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		$this->value = $value;
		return $this;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
		return $this;
	}
}