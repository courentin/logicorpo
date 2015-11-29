<?php
namespace LogiCorpoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
* 
*/
class ProduitsCommandeType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('produit', 'entity', [
				'class' => 'LogiCorpoBundle:Produit',
				'query_builder' => function(EntityRepository $rep) {
					return $rep->createQueryBuilder('p')->where('p.dispo = true');
				}
			])
			->add('quantite', 'integer');
	}

	public function getParant()
	{
		return 'choice';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'produits_commande';
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'LogiCorpoBundle\Entity\ProduitsCommande'
		));
	}
}