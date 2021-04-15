<?php

namespace App\Entity;

use App\Repository\DifficultyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DifficultyRepository::class)
 */
class Difficulty
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $name;

  /**
   * @ORM\OneToMany(targetEntity=Move::class, mappedBy="difficulty")
   */
  private $moves;

  public function __construct()
  {
      $this->moves = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  /**
   * @return Collection|Move[]
   */
  public function getMoves(): Collection
  {
      return $this->moves;
  }

  public function addMove(Move $move): self
  {
      if (!$this->moves->contains($move)) {
          $this->moves[] = $move;
          $move->setDifficulty($this);
      }

      return $this;
  }

  public function removeMove(Move $move): self
  {
      if ($this->moves->removeElement($move)) {
          // set the owning side to null (unless already changed)
          if ($move->getDifficulty() === $this) {
              $move->setDifficulty(null);
          }
      }

      return $this;
  }
}
