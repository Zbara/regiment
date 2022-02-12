<?php

namespace App\Entity;

use App\Repository\RegimentUsersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegimentUsersRepository::class)]
class RegimentUsers
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', unique: true)]
    private $socId;

    #[ORM\Column(type: 'integer')]
    private $level;

    #[ORM\Column(type: 'integer')]
    private $sut;

    #[ORM\Column(type: 'integer')]
    private $used_talents;

    #[ORM\Column(type: 'integer')]
    private $login_time;

    #[ORM\Column(type: 'integer')]
    private $total_damage;

    #[ORM\Column(type: 'integer')]
    private $created;

    #[ORM\Column(type: 'object')]
    private $achievements;

    #[ORM\Column(type: 'string', length: 255)]
    private $first_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $last_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $photo_50;

    #[ORM\Column(type: 'integer')]
    private $updateTime;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSocId(): ?int
    {
        return $this->socId;
    }

    public function setSocId(int $socId): self
    {
        $this->socId = $socId;

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

    public function getLoginTime(): ?int
    {
        return $this->login_time;
    }

    public function setLoginTime(int $login_time): self
    {
        $this->login_time = $login_time;

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

    public function getCreated(): ?int
    {
        return $this->created;
    }

    public function setCreated(int $created): self
    {
        $this->created = $created;

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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

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

    public function getPhoto50(): ?string
    {
        return $this->photo_50;
    }

    public function setPhoto50(string $photo_50): self
    {
        $this->photo_50 = $photo_50;

        return $this;
    }

    public function getUpdateTime(): ?int
    {
        return $this->updateTime;
    }

    public function setUpdateTime(int $updateTime): self
    {
        $this->updateTime = $updateTime;

        return $this;
    }
}
