<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
   * @ORM\ManyToMany(targetEntity=Move::class, mappedBy="categories")
   */
  private $moves;

  public function __construct()
  {
    $this->competitions = new ArrayCollection();
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
          $move->addCategory($this);
      }

      return $this;
  }

  public function removeMove(Move $move): self
  {
      if ($this->moves->removeElement($move)) {
          $move->removeCategory($this);
      }

      return $this;
  }
}
