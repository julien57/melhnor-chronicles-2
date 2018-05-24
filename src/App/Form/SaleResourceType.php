<?php

namespace App\Form;

use App\Entity\Resource;
use App\Model\SaleResourceDTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleResourceType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('resource', EntityType::class, [
                'class' => Resource::class,
                'choice_label' => 'name',
                'multiple' => false,
            ])
            ->add('quantity', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SaleResourceDTO::class
        ]);
    }
}
