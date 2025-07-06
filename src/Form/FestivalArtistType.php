<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Artist;
use App\Entity\FestivalArtist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FestivalArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('artist', EntityType::class, [
                'class' => Artist::class,
                'choice_label' => 'name', // adjust to the property you want displayed
                'placeholder' => 'Select an Artist',
            ])
            ->add('stageName', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FestivalArtist::class,
        ]);
    }
}
