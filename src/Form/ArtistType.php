<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label'=>'Name',
                'attr' => ['placeholder' => 'Name', 'pattern'=> '[a-zA-Z]+', 'required'=>'true']])
            ->add('genre',  TextType::class,['label'=>'Genre',
                'attr' => ['placeholder' => 'Genre', 'pattern'=> '[a-zA-Z]+', 'required'=>'true']])
            ->add('description', TextareaType::class, ['label'=>'Description',
                'attr' => ['placeholder' => 'Description', 'required'=>'false']])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Artist::class,]);
    }
}
