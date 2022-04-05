<?php

namespace App\Entity;

use App\Repository\RegimentStatsUsersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegimentStatsUsersRepository::class)]
class RegimentStatsUsers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: RegimentUsers::class, cascade: ['persist', 'remove'], inversedBy: 'regimentStatsUsers')]
    private $user;

    #[ORM\Column(type: 'date')]
    private $created;

    #[ORM\Column(type: 'integer')]
    private $level = 0;

    #[ORM\Column(type: 'integer')]
    private $sut = 0;

    #[ORM\Column(type: 'integer')]
    private $used_talents = 0;

    #[ORM\Column(type: 'integer')]
    private $total_damage = 0;

    #[ORM\Column(type: 'object')]
    private $achievements = [];

    #[ORM\Column(type: 'integer')]
    private $experience = 0;

    #[ORM\Column(type: 'integer')]
    private $update_time = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?RegimentUsers
    {
        return $this->user;
    }

    public function setUser(?RegimentUsers $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getSut(): ?int
    {
        return $this->sut;
    }

    public function setSut(int $sut): self
    {
        $this->sut = $sut;

        return $this;
    }

    public function getUsedTalents(): ?int
    {
        return $this->used_talents;
    }

    public function setUsedTalents(int $used_talents): self
    {
        $this->used_talents = $used_talents;

        return $this;
    }

    public function getTotalDamage(): ?int
    {
        return $this->total_damage;
    }

    public function setTotalDamage(int $total_damage): self
    {
        $this->total_damage = $total_damage;

        return $this;
    }

    public function getAchievements()
    {
        return $this->achievements;
    }

    public function setAchievements($achievements): self
    {
        $this->achievements = $achievements;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getUpdateTime(): ?int
    {
        return $this->update_time;
    }

    public function setUpdateTime(int $update_time): self
    {
        $this->update_time = $update_time;

        return $this;
    }
}
