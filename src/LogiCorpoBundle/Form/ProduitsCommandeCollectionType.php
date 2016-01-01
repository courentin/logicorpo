<?php
namespace LogiCorpoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
* 
*/
class ProduitsCommandeCollectionType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		    ->add('produits', 'collection', array(
		    	'type' => 'produits_commande',
				'allow_add'          => true,
				'allow_delete'       => true,
				'cascade_validation' => true,
				'by_reference'       => false
		    ));
	}

	public function getParant()
	{
		return 'collection';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'produits_commande_collection';
	}
}