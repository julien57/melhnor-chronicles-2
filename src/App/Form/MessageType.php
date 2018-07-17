<?php

namespace App\Form;

use App\Entity\Player;
use App\Model\WriteMessageDTO;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class)
            ->add('message', TextareaType::class, [
                'attr' => ['cols' => '80'],
            ])

        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            if ($options['idRecipient'] !== null) {
                $event->getForm()->add('recipient', EntityType::class, [
                    'class' => Player::class,
                    'query_builder' => function (EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('p')
                            ->where('p.id = :id')
                            ->setParameter('id', $options['idRecipient']);
                    },
                    'choice_label' => 'username',
                ]);
            } else {
                $event->getForm()->add('recipient', EntityType::class, [
                    'class' => Player::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                            ->orderBy('p.username', 'ASC');
                    },
                    'choice_label' => 'username',
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WriteMessageDTO::class,
            'idRecipient' => 'recipient',
        ]);
    }
}
