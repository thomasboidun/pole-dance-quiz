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
   * @Route(
   *  "/quiz/start", 
   *  name="start_quiz",
   *  condition="context.getMethod() in ['GET']"
   * )
   */
  public function start(MoveRepository $moveRepository): Response
  {
    $moves = $moveRepository->findAll();
    shuffle($moves);

    $moves = array_slice($moves, 0, 10, false);

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
   * @Route(
   *  "/quiz/{id}/send-replies", 
   *  name="send_quiz_replies",
   *  condition="context.getMethod() in ['POST']"
   * )
   */
  public function send_replies(int $id = null, Request $request): Response
  {
    $quiz = $this->findQuizById($id);

    $replies = $this->convertJSONRepliesToObjectReplies($request->toArray());

    $score = $this->generateScore($quiz->getMoves(), $replies);

    $entityManager = $this->getDoctrine()->getManager();

    $quiz->setReplies($replies)->setScore($score);

    $entityManager->flush();

    $response =  new Response();
    $response->setContent($quiz->getScore());
    $response->headers->set('Content-Type', 'application/json');
    $response->setStatusCode(Response::HTTP_OK);
    return $response->send();
  }

  /**
   * @Route(
   *  "/quiz/{id}/show_result", 
   *  name="show_quiz_result",
   *  condition="context.getMethod(['GET'])"
   * )
   */
  public function show_result(int $id = null, Request $request): Response
  {
    $quiz = $this->findQuizById($id);

    if($request->isMethod('POST') && isset($_POST['player'])) {
      $quiz->setPlayer($_POST['player']);

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->flush();

      return $this->redirectToRoute('quiz_scoreboard');
    }

    return $this->render('quiz/result.html.twig', [
      'quiz_id' => $quiz->getId(),
      'maxScore' => $this->getMaxScore(count($quiz->getMoves())),
      'score' => $quiz->getScore(),
      'moves' => $quiz->getMoves(),
      'replies' => $quiz->getReplies(),
    ]);
  }

  /**
   * @Route(
   *  "/quiz/scoreboard", 
   *  name="quiz_scoreboard",
   *  condition="context.getMethod(['GET'])"
   * )
   */
  public function show_scoreboard(QuizRepository $quizRepository)
  {
    $quizs = $quizRepository->findAll();

    usort($quizs, function($a, $b) {
      if($a->getScore() == $b->getScore()) {
        return 0;
      } else if ($a->getScore() > $b->getScore()) {
        return -1;
      } else {
        return 1;
      }
    });

    return $this->render('quiz/scoreboard.html.twig', [
      'quizs' => $quizs
    ]);
  }

  private function findQuizById(int $id): Quiz
  {
    $quizRepository = $this->getDoctrine()->getManager()->getRepository(Quiz::class);
    $quiz = $quizRepository->find(intval($id, 10));

    if (!$quiz) {
      throw $this->createNotFoundException('No quiz found for id ' . $id);
    }

    return $quiz;
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

  private function convertJSONRepliesToObjectReplies(array $data): array
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

  private function getMaxScore(int $nbrQuestion)
  {
    $score = 0;
    $bonus = 1;

    for ($i = 0; $i < $nbrQuestion; $i++) {
      $score += 1 * $bonus;
      $bonus++;
    }

    return $score;
  }
}
