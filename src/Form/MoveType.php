<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Difficulty;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MoveType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', TextType::class, [
        'label' => 'Move name:',
        'attr' => [
          'placeholder' => 'e.g. Rainbow',
        ],
        'required' => true,
      ])
      ->add('video_uri', TextType::class, [
        'label' => 'Video URI:',
        'attr' => [
          'placeholder' => 'e.g. http://www.something.com'
        ]
      ])
      ->add('difficulty', EntityType::class, [
        'label' => 'Choose difficulty:',
        // looks for choices from this entity
        'class' => Difficulty::class,

        // uses the User.username property as the visible option string
        'choice_label' => 'name',

        // used to render a select box, check boxes or radios
        // 'multiple' => true,
        // 'expanded' => true,
      ])
      ->add('categories', EntityType::class, [
        'label' => 'Choose categories:',
        // looks for choices from this entity
        'class' => Category::class,

        // uses the User.username property as the visible option string
        'choice_label' => 'name',

        // used to render a select box, check boxes or radios
        'multiple' => true,
        'expanded' => true,
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
