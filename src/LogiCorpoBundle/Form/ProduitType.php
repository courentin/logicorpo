<?php
namespace LogiCorpoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProduitType extends AbstractType
{

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
                ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'produit';
    }
}
