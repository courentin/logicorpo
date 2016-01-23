<?php
namespace LogiCorpoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RangeType extends AbstractType
{

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('value', NumberType::class);
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