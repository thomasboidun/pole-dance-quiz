<?php

namespace App\Controller;


use App\Entity\Move;
use App\Entity\Quiz;
use App\Repository\MoveRepository;
use App\Repository\QuizRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class QuizController extends AbstractController
{
  /**
   * @Route("/quiz", name="quiz")
   */
  public function start_quiz(Request $request, MoveRepository $moveRepository, QuizRepository $quizRepository): Response
  {
    $moves = $moveRepository->findAll();
    shuffle($moves);

    $moves = array_slice($moves, 0, 3, false);

    $quiz = new Quiz();

    $quiz->setPlayer('anonymous player');
    $quiz->setScore(0);

    foreach ($moves as $index => $move) {

      $moves_id[] = (object) [
        'id' => $move->getId(),
        'name' => $move->getName()
      ];

      $choices[] = $this->setChoices($move);
    }

    $quiz->setMoves($moves_id);
    $quiz->setChoices($choices);

    $em = $this->getDoctrine()->getManager();
    $em->persist($quiz);
    $em->flush();

    return $this->render('quiz/index.html.twig', [
      'nbrQuestions' => count($moves),
      'moves' => $moves,
      'quiz' => $quiz,
    ]);
  }

  /**
   * @Route("/quiz/{id}", name="quiz_result")
   */
  public function show_result(int $id = null, Request $request, QuizRepository $quizRepository): Response
  {
    $quiz = $quizRepository->find(intval($id, 10));

    if (!$quiz) {
      throw $this->createNotFoundException('No quiz found for id ' . $id);
    }

    if ($request->isMethod('POST')) {
      $em = $this->getDoctrine()->getManager();
      $replies = $this->convertBodyContent($request->toArray());
      $score = $this->generateScore($quiz->getMoves(), $replies);
      $quiz->setReplies($replies)
        ->setScore($score);
      $em->flush();

      // generate response
      $response =  new Response();
      $response->setContent(json_encode([
        'quiz_id' => $quiz->getId(),
        'score' => $score
      ]));
      $response->headers->set('Content-Type', 'application/json');
      $response->setStatusCode(Response::HTTP_OK);
      return $response->send();
    }

    return $this->render('quiz/result.html.twig', [
      'score' => $quiz->getScore(),
      'moves' => $quiz->getMoves(),
      'replies' => $quiz->getReplies(),
    ]);
  }

  private function setChoices(Move $move): array
  {
    // add current move in the table of choices
    $choices[] = (object) [
      'id' => $move->getId(),
      'name' => $move->getName()
    ];

    // get categories of current move
    $categories = $move->getCategories();

    // and select one at random
    $randomCat = $categories[random_int(0, count($categories) - 1)];

    // then get move repository
    $moveRepo = $this->getDoctrine()->getRepository(Move::class);

    // and retrieve the list of moves with the same category
    $moves = $moveRepo->findAllByCategoryId($randomCat->getId());

    shuffle($moves);

    // finally add two moves in the table of choices
    foreach ($moves as $key => $value) {

      if ($value->getId() !== $move->getId()) {
        $choices[] = (object) [
          'id' => $value->getId(),
          'name' => $value->getName()
        ];
      }

      if (count($choices) >= 3) break;
    }

    shuffle($choices);

    // and return it
    return $choices;
  }

  private function convertBodyContent(array $data): array
  {
    foreach ($data as $key => $value) {
      $replies[] = (object) [
        'id' => $value[0],
        'name' => $value[1]
      ];
    }

    return $replies;
  }

  private function generateScore($moves, $replies): int
  {
    $score = 0;
    $bonus = 1;

    $i = 0;
    foreach ($moves as $key => $move) {
      $reply = $replies[$i];
      if ($move->id == $reply->id) {
        $score += 1 * $bonus;
        $bonus++;
      } else {
        $bonus = 1;
      }
      $i++;
    }

    return $score;
  }
}
