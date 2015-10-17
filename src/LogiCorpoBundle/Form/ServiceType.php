<?php
namespace LogiCorpoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServiceType extends AbstractType
{
	private $service;

	public function __construct(\LogiCorpoBundle\Entity\Service $service) {
		$this->service = $service;
	}

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    	$builder->add('date', 'date', ['mapped' => false])
                ->add('debut', 'time', ['label' => 'Début du service'])
                ->add('fin', 'time', ['label' => 'Fin du service'])
                ->add('debutCommande', 'time', ['label' => 'Début des commandes'])
    			->add('finCommande', 'time', ['label' => 'Fin des commandes'])
    			->add($options['submit'], 'submit');
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'submit' => 'Valider',
            )
        );

        $resolver->addAllowedTypes(
            array(
                'submit' => 'string'
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'service';
    }
}
