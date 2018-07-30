<?php

namespace App\Form;

use App\Entity\KingdomBuilding;
use App\Model\ArmyRecruitmentDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecruitmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {

                if ($options['army'] !== null) {
                    /** @var KingdomBuilding $kingdomBuilding */
                    foreach ($options['buildings'] as $kingdomBuilding) {

                        if ($kingdomBuilding->getBuilding()->getId() === KingdomBuilding::BUILDING_RECRUITMENT_SOLDIER) {

                            $event->getForm()->add('soldier', TextType::class, [
                                'required' => false
                            ]);
                        }
                        if ($kingdomBuilding->getBuilding()->getId() === KingdomBuilding::BUILDING_RECRUITMENT_ARCHERY) {

                            $event->getForm()->add('archer', TextType::class, [
                                'required' => false
                            ]);
                        }
                        if ($kingdomBuilding->getBuilding()->getId() === KingdomBuilding::BUILDING_RECRUITMENT_STABLE) {

                            $event->getForm()->add('horseman', TextType::class, [
                                'required' => false
                            ]);
                        }
                        if ($kingdomBuilding->getBuilding()->getId() === KingdomBuilding::BUILDING_RECRUITMENT_BOAT) {

                            $event->getForm()->add('boat', TextType::class, [
                                'required' => false
                            ]);
                        }
                    }
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArmyRecruitmentDTO::class,
            'army' => 'army',
            'buildings' => 'buildings'
        ]);
    }
}