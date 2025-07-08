<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\UserDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [ 'attr' => [ 'placeholder' => 'First Name' , 'pattern' => '[A-Z][a-z]{2,15}' ] ])
            ->add('lastName', TextType::class,  [ 'attr' => [ 'placeholder' => 'Last Name' , 'pattern' => '[A-Z][a-z]{2,15}']])
            ->add('age', NumberType::class, ['attr' => ['placeholder'=>'Age','pattern' => '[1-9][0-9]{0,1}'], ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserDetails::class,
        ]);
    }
}
