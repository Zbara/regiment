<?php

namespace App\Entity;

use App\Repository\StatsLogsVisitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatsLogsVisitRepository::class)]
class StatsLogsVisit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $ip;

    #[ORM\Column(type: 'string', length: 255)]
    private $time;

    #[ORM\Column(type: 'string', length: 255)]
    private $referar;

    #[ORM\Column(type: 'string', length: 255)]
    private $ua;

    #[ORM\Column(type: 'string', length: 255)]
    private $page;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getReferar(): ?string
    {
        return $this->referar;
    }

    public function setReferar(string $referar): self
    {
        $this->referar = $referar;

        return $this;
    }

    public function getUa(): ?string
    {
        return $this->ua;
    }

    public function setUa(string $ua): self
    {
        $this->ua = $ua;

        return $this;
    }

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(string $page): self
    {
        $this->page = $page;

        return $this;
    }
}
