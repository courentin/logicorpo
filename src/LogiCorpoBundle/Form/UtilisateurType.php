<?php
namespace LogiCorpoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UtilisateurType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('nom', 'text')
			->add('prenom', 'text', ['label' => 'Prénom'])
			->add('username', 'text', ['label' => 'Login'])
			->add('mail', 'email')
			->add('rang', 'entity', [
				'class' => 'LogiCorpoBundle:Rang'
			]);
			if($options['solde']) {
				$builder->add('solde', 'money', [
					'label' => 'Solde initial',
					'data'  => 0
				]);
			}
	}

	public function getName()
	{
		return 'utilisateur';
	}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'solde' => true,
            )
        );

        $resolver->addAllowedTypes(
            array(
                'solde' => 'boolean'
            )
        );
    }

}