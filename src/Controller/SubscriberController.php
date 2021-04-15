<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Subscriber;
use App\Form\SubscriberType;

use Symfony\Component\Mailer\MailerInterface;


class SubscriberController extends AbstractController
{
  /**
   * @Route("/subscribe", name="subscribe")
   */
  public function show_the_subscribe_page(Request $request, MailerInterface $mailer): Response
  {
    $subscriber = new Subscriber();
    $form = $this->createForm(SubscriberType::class, $subscriber);
    
    // if form is submitted
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($subscriber);
      $em->flush();
      
      $repository = $this->getDoctrine()->getRepository(Subscriber::class);
      $context = $repository->findOneBy(
        array("email" => $subscriber->getEmail())
      );

      $email = MailerController::sendSubscribeMail($context);
      $mailer->send($email);
    }

    return $this->render('subscriber/index.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/subscribe/check/{id}", name="subscribe_check")
   */
  public function check_the_subscriber_by_id(Request $request, MailerInterface $mailer)
  {
    $routeParameters = $request->attributes->get('_route_params');
    $id = intval($routeParameters['id'], 10);

    if ($id) {
      $repository = $this->getDoctrine()->getRepository(Subscriber::class);

      $subscriber = $repository->find($id);

      if(!$subscriber) {
        return $this->redirectToRoute('subscribe');
      }

      $subscriber->setIsChecked(true);
      
      $em = $this->getDoctrine()->getManager();
      $em->flush();

      return $this->render('subscriber_thanks/index.html.twig', [
        'subscriber_email' => $subscriber->getEmail()
      ]);

    } else {
      $this->show_the_subscribe_page($request, $mailer);
    }
  }
}
