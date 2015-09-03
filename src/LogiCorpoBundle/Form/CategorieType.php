<?php
namespace LogiCorpoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategorieType extends AbstractType
{
	private $categorie;

	public function __construct(\LogiCorpoBundle\Entity\Categorie $categorie) {
		$this->categorie = $categorie;
	}

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    	$builder->add('libelle', 'text')
    			->add('libellePluriel', 'text', ['label' => 'Libelle au pluriel'])
    			->add('ordre', 'number')
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
        return 'produit_categorie';
    }
}
