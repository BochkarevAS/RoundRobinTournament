<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
#[ORM\Table(name: 'tournaments')]
#[UniqueEntity(fields: ['name'], message: 'Такой туринр уже есть.')]
class Tournament
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Название туринра обязательно.')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(type: Types::STRING, length: 64, unique: true, nullable: true)]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: Team::class)]
    #[ORM\JoinTable(name: 'tournaments_teams')]
    private ?Collection $teams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function getTeams(): ArrayCollection|Collection|null
    {
        return $this->teams;
    }

    public function addTeam(Team $team): void
    {
        if ($this->teams->contains($team)) {
            return;
        }

        $this->teams[] = $team;
    }

    public function removeTeam(Team $team): void
    {
        if (!$this->teams->contains($team)) {
            return;
        }

        $this->teams->removeElement($team);
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}