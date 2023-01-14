<?php

namespace App\Form;

use App\Entity\FlashCard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddFlashCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Front', TextType::class, [
                'label' => 'Recto',
            ])
            ->add('Back', TextType::class, [
                'label' => 'Verso',
            ])
            ->add('Type', ChoiceType::class,[
                'label' => false,
                'attr' => ['class' => 'select-form'],
                'choices' => [
                    'Texte' => 1,
                    'Url' => 2,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FlashCard::class,
        ]);
    }
}
