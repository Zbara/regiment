<?php

namespace App\Entity;

use App\Repository\UsersScriptRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersScriptRepository::class)]
class UsersScript
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $platformId;

    #[ORM\Column(type: 'integer')]
    private $created;

    #[ORM\Column(type: 'string', length: 255)]
    private $photo_50;

    #[ORM\Column(type: 'string', length: 255)]
    private $last_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $first_name;

    #[ORM\Column(type: 'integer')]
    private $lastTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlatformId(): ?int
    {
        return $this->platformId;
    }

    public function setPlatformId(int $platformId): self
    {
        $this->platformId = $platformId;

        return $this;
    }

    public function getCreated(): ?int
    {
        return $this->created;
    }

    public function setCreated(int $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getPhoto50(): ?string
    {
        return $this->photo_50;
    }

    public function setPhoto50(string $photo_50): self
    {
        $this->photo_50 = $photo_50;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastTime(): ?int
    {
        return $this->lastTime;
    }

    public function setLastTime(int $lastTime): self
    {
        $this->lastTime = $lastTime;

        return $this;
    }
}
