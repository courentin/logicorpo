<?php
namespace LogiCorpoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProduitType extends AbstractType
{
	private $produit;

	public function __construct(\LogiCorpoBundle\Entity\Produit $produit) {
		$this->produit = $produit;
	}

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('libelle', 'text',['label' => 'Libellé'])
                ->add('categorie', 'entity', [
                    'class'    => 'LogiCorpoBundle:Categorie',
                ])
                ->add('dispo', 'checkbox', ['label' => 'Disponible'])
                ->add('stock', 'number', ['required' => false])
                ->add('prixVente','money', ['label' => 'Prix de vente'])
                ->add('prixAchat', 'money', ['label' => 'Prix de revient'])
                ->add('reduction', 'checkbox', ['label' => 'Appliquer les réductions'])
                ->add('supplementsDisponible', 'entity', [
                    'class'    => 'LogiCorpoBundle:Supplement',
                    'multiple' => true,
                    'label'    => 'Suppléments disponibles'
                ])
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
        return 'produit';
    }
}
