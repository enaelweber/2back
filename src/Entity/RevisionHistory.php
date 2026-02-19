<?php

namespace App\Entity;

use App\Repository\RevisionHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RevisionHistoryRepository::class)]
class RevisionHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'revisionHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Page $page = null;

    #[ORM\ManyToOne(inversedBy: 'revisionHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $wikitext = null;

    #[ORM\Column]
    private ?\DateTime $date = null;

    #[ORM\Column]
    private ?int $changes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPageId(): ?Page
    {
        return $this->page;
    }

    public function setPageId(?Page $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function getAuthorId(): ?User
    {
        return $this->author;
    }

    public function setAuthorId(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getWikitext(): ?string
    {
        return $this->wikitext;
    }

    public function setWikitext(?string $wikitext): static
    {
        $this->wikitext = $wikitext;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getChanges(): ?int
    {
        return $this->changes;
    }

    public function setChanges(int $changes): static
    {
        $this->changes = $changes;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }
}
