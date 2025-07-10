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
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'min' => (new \DateTime('today'))->format('Y-m-d'),
                ]])
            ->add('endDate', DateType::class)
            ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $edition = $event->getData();
            if ($edition->getStartDate() && $edition->getEndDate() &&
                $edition->getEndDate() <= $edition->getStartDate()) {
                $event->getForm()->get('endDate')->addError(new FormError(
                    'The end date must be after the start date'
                ));
            }


        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Festival::class,]);
    }
}
