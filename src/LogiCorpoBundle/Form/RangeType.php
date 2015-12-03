<?php
namespace LogiCorpoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RangeType extends AbstractType
{

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('value', 'number');
	}

	public function getName()
	{
		return 'range';
	}

	public function getParent()
	{
		return 'number';
	}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $resolver->addAllowedTypes(
            array(
                'min' => 'number',
                'max' => 'number'
            )
        );
    }
}