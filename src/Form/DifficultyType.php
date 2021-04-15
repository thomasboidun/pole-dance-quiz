<?php

namespace App\Form;

use App\Entity\Difficulty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DifficultyType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', TextType::class, [
        'label' => 'Difficulty name:',
        'attr' => [
          'placeholder' => 'e.g. Extreme',
        ],
        'required' => true,
      ])
      ->add('send', SubmitType::class, [
        'label' => 'Send',
        'attr' => [
          'class' => 'btn-primary'
        ]
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' == Difficulty::class,
    ]);
  }
}
