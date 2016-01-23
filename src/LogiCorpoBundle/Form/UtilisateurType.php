<?php
namespace LogiCorpoBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UtilisateurType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('nom', TextType::class)
			->add('prenom', TextType::class, ['label' => 'Prénom'])
			->add('username', TextType::class, ['label' => 'Login'])
			->add('mail', EmailType::class)
			->add('rang', EntityType::class, [
				'class' => 'LogiCorpoBundle:Rang'
			]);
			if($options['solde']) {
				$builder->add('solde', MoneyType::class, [
					'label' => 'Solde initial',
					'data'  => 0
				]);
			}
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			array(
				'solde' => true,
			)
		);

		$resolver->setAllowedTypes('solde', 'boolean');
	}
}