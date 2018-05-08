<?php

namespace App\Form;

use App\Entity\Avatar;
use App\Entity\Region;
use App\Model\CreatePlayerDTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->add('mail', EmailType::class)
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
                'multiple' => false,
            ])
            ->add('avatar', EntityType::class, [
                'class' => Avatar::class,
                'choice_label' => 'id_avatar',
                'multiple' => false,
            ])
            ->add('inscription', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CreatePlayerDTO::class,
        ]);
    }
}
