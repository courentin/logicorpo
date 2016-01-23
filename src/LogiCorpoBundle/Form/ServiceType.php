<?php
namespace LogiCorpoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('date', DateType::class, ['mapped' => false])
			->add('debut', DateTimeType::class, ['label' => 'Début du service'])
			->add('fin', DateTimeType::class, ['label' => 'Fin du service'])
			->add('debutCommande', DateTimeType::class, ['label' => 'Début des commandes'])
			->add('finCommande', DateTimeType::class, ['label' => 'Fin des commandes']);
	}
}