<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Festival;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FestivalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label'=>'Name',
                'attr' => ['placeholder' => 'Name', 'pattern'=> '[a-zA-Z]+', 'required'=>'true']
                ,
                ])
            ->add('price', NumberType::class, ['attr' => ['placeholder' => 'Price',]])
            ->add('location', TextType::class, ['attr' => ['placeholder' => 'Location',]])
            ->add('startDate', DateType::class)
            ->add('endDate', DateType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Festival::class,]);
    }
}
