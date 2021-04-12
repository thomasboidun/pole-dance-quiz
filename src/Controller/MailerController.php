<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\Subscriber;


class MailerController extends AbstractController
{
  public static function sendSubscribeMail(Subscriber $subscriber): TemplatedEmail
  {
    $email = (new TemplatedEmail())
      ->from('poledance@quiz.com')
      ->to($subscriber->getEmail())
      ->subject('Subscribe now!')
      ->htmlTemplate('emails/subscribe.html.twig')
      ->context(array(
        "subscriber_id" => $subscriber->getId(),
        "subscriber_email" => $subscriber->getEmail(),
        "subscriber_check" => $subscriber->getIsChecked()
      ));

    return $email;

    // ...
  }
}
