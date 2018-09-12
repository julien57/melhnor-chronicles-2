<?php

namespace App\Form;

use App\Entity\Army;
use App\Entity\KingdomArmy;
use App\Model\ArmyStrategyDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArmyStrategyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
                if ($options['kingdomArmys'] !== null) {
                    /** @var KingdomArmy $kingdomArmy */
                    foreach ($options['kingdomArmys'] as $kingdomArmy) {
                        if ($kingdomArmy->getArmy()->getId() === Army::SOLDIER_ID) {
                            $event->getForm()->add('soldier', TextType::class, [
                                'required' => false,
                            ]);
                        }
                        if ($kingdomArmy->getArmy()->getId() === Army::ARCHER_ID) {
                            $event->getForm()->add('archer', TextType::class, [
                                'required' => false,
                            ]);
                        }
                        if ($kingdomArmy->getArmy()->getId() === Army::HORSEMAN_ID) {
                            $event->getForm()->add('horseman', TextType::class, [
                                'required' => false,
                            ]);
                        }
                        if ($kingdomArmy->getArmy()->getId() === Army::BOAT_ID) {
                            $event->getForm()->add('boat', TextType::class, [
                                'required' => false,
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
            'data_class' => ArmyStrategyDTO::class,
            'kingdomArmys' => 'kingdomArmys',
        ]);
    }
}
