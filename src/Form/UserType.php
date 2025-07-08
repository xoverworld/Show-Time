<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class,['attr' => ['placeholder' => 'Email' , 'pattern' => '[A-Za-z0-9]{2,12}@[a-zA-Z]{2,10}\.[a-zA-Z]{2,10}']])
            ->add('password', TextType::class,['attr' => ['placeholder' => 'Password' , 'pattern' => '[A-Za-z0-9]{3,15}']])
            ->add('userDetails', UserDetailsType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class,]);
    }
}
