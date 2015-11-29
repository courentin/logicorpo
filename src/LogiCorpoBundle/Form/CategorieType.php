<?php
namespace LogiCorpoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategorieType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('libelle', 'text')
    			->add('libellePluriel', 'text', ['label' => 'Libelle au pluriel'])
    			->add('ordre', 'number');
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'produit_categorie';
    }
}
