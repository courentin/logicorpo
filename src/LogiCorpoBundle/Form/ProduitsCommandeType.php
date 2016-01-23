<?php
namespace LogiCorpoBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
			->add('produit', EntityType::class, [
				'class' => 'LogiCorpoBundle:Produit',
				'query_builder' => function(EntityRepository $rep) {
					return $rep->createQueryBuilder('p')->where('p.dispo = true');
				}
			])
			->add('quantite', IntegerType::class);
	}

	public function getParant()
	{
		return 'choice';
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'LogiCorpoBundle\Entity\ProduitsCommande'
		));
	}
}