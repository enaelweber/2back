<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $page_type = null;

    #[ORM\Column]
    private ?int $current_revision = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'pages')]
    private Collection $categories;

    /**
     * @var Collection<int, RevisionHistory>
     */
    #[ORM\OneToMany(targetEntity: RevisionHistory::class, mappedBy: 'page', orphanRemoval: true)]
    private Collection $revisionHistories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->revisionHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPageType(): ?int
    {
        return $this->page_type;
    }

    public function setPageType(int $page_type): static
    {
        $this->page_type = $page_type;

        return $this;
    }

    public function getCurrentRevision(): ?int
    {
        return $this->current_revision;
    }

    public function setCurrentRevision(int $current_revision): static
    {
        $this->current_revision = $current_revision;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, RevisionHistory>
     */
    public function getRevisionHistories(): Collection
    {
        return $this->revisionHistories;
    }

    public function addRevisionHistory(RevisionHistory $revisionHistory): static
    {
        if (!$this->revisionHistories->contains($revisionHistory)) {
            $this->revisionHistories->add($revisionHistory);
            $revisionHistory->setPageId($this);
        }

        return $this;
    }

    public function removeRevisionHistory(RevisionHistory $revisionHistory): static
    {
        if ($this->revisionHistories->removeElement($revisionHistory)) {
            // set the owning side to null (unless already changed)
            if ($revisionHistory->getPageId() === $this) {
                $revisionHistory->setPageId(null);
            }
        }

        return $this;
    }
}
