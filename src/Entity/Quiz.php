<?php

namespace App\Entity;

use App\Repository\MoveRepository;
use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizRepository::class)
 */
class Quiz
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array", nullable=false)
     */
    private $moves = [];

    /**
     * @ORM\Column(type="array", nullable=false)
     */
    private $choices = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $replies = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $player;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMoves(): ?array
    {
        return $this->moves;
    }

    public function setMoves(array $moves): self
    {
      $this->moves = $moves;

      return $this;
    }

    public function getChoices(): ?array
    {
        return $this->choices;
    }

    public function setChoices(array $choices): self
    {
        $this->choices = $choices;

        return $this;
    }

    public function getReplies(): ?array
    {
        return $this->replies;
    }

    public function setReplies(array $replies): self
    {
        $this->replies = $replies;

        return $this;
    }

    public function getPlayer(): ?string
    {
        return $this->player;
    }

    public function setPlayer(?string $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }
}
