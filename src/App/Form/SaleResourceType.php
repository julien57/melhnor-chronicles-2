<?php

namespace App\Form;

use App\Entity\Resource;
use App\Model\SaleResourceDTO;
use App\Repository\ResourceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $kingdom = $options['kingdom'];

        $builder
            ->add('resource', EntityType::class, [
                'class' => Resource::class,
                'query_builder' => function (ResourceRepository $resourceRepository) use ($kingdom) {
                    return $resourceRepository->createQueryBuilder('resource')
                        ->innerJoin('resource.kingdomResources', 'kingdom_resources')
                        ->where('kingdom_resources.kingdom = :kingdom')
                        ->setParameter('kingdom', $kingdom);
                },
                'choice_label' => 'name',
                'multiple' => false,
            ])
            ->add('quantity', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SaleResourceDTO::class,
        ]);

        $resolver->setRequired('kingdom');
    }
}
