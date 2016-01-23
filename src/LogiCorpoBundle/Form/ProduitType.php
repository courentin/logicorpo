<?php
namespace LogiCorpoBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProduitType extends AbstractType
{

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('libelle', TextType::class,['label' => 'Libellé'])
                ->add('categorie', EntityType::class, [
                    'class'    => 'LogiCorpoBundle:Categorie',
                ])
                ->add('dispo', CheckboxType::class, ['label' => 'Disponible'])
                ->add('stock', NumberType::class, ['required' => false])
                ->add('prixVente', MoneyType::class, ['label' => 'Prix de vente'])
                ->add('prixAchat', MoneyType::class, ['label' => 'Prix de revient'])
                ->add('reduction', CheckboxType::class, ['label' => 'Appliquer les réductions'])
                ->add('supplementsDisponible', EntityType::class, [
                    'class'    => 'LogiCorpoBundle:Supplement',
                    'multiple' => true,
                    'label'    => 'Suppléments disponibles'
                ]);
    }
}
