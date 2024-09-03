<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isCompleted = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsCompleted(): ?bool
    {
        return $this->isCompleted;
    }
    public function setIsCompleted(bool $isCompleted): static
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function jsonSerialize():array
    {
        return [
            "id" => $this->getId(),
            "title" => $this->getTitle(),
            "content"=> $this->getContent(),
            "isCompleted" => $this->getIsCompleted()
        ];
    }
}
