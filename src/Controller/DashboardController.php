<?php

namespace App\Controller;

use App\Entity\Move;
use App\Form\MoveType;

use App\Entity\Category;
use App\Form\CategoryType;

use App\Entity\Difficulty;
use App\Form\DifficultyType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
  /**
   * @Route("/dashboard/{entity}", name="dashboard")
   */
  public function index($entity = null, Request $request): Response
  {
    if ($entity) {
      $instance = $this->getInstance($entity);

      $form = $this->getForm($entity, $instance);
      $form->handleRequest($request);

      $em = $this->getDoctrine()->getManager();

      if ($form->isSubmitted() && $form->isValid()) {
        // form process
        $em->persist($instance);
        $em->flush();
      }
    }

    return $this->render('dashboard/index.html.twig', [
      'entity' => $entity,
      'form' => isset($form) ? $form->createView(): null,
    ]);
  }

  private function getInstance(string $entity)
  {
    switch ($entity) {
      case 'move':
        return new Move();
      case 'category':
        return new Category();
      case 'difficulty':
        return new Difficulty();
      default:
        return null;
    }
  }

  private function getForm(string $entity, $instance)
  {
    switch ($entity) {
      case 'move':
        return $this->createForm(MoveType::class, $instance);
      case 'category':
        return $this->createForm(CategoryType::class, $instance);
      case 'difficulty':
        return $this->createForm(DifficultyType::class, $instance);
      default:
        return null;
    }
  }
}
