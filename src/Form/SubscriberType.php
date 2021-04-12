<?php

namespace App\Form;

use App\Entity\Subscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriberType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('email', EmailType::class, [
        'label' => 'Enter your email address:',
        'attr' => [
          'placeholder' => 'example@email.com',
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
      'data_class' == Subscriber::class,
    ]);
  }
}
