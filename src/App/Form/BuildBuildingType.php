<?php

namespace App\Form;

use App\Entity\Building;
use App\Model\BuildBuildingDTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuildBuildingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('building', EntityType::class, [
                'class' => Building::class,
                'choice_label' => 'name',
                'multiple' => false,
            ])
            ->add('construire', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BuildBuildingDTO::class,
        ]);
    }
}
