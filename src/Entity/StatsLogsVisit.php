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
    private ?string $ip;

    #[ORM\Column(type: 'string', length: 255)]
    private $time;

    #[ORM\Column(type: 'string', length: 255)]
    private $page;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private $platform_id = 0;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $platform;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $version;

    #[ORM\Column(type: 'string', length: 255)]
    private $browser;

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

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(string $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getPlatformId(): ?int
    {
        return $this->platform_id;
    }

    public function setPlatformId(int $platform_id): self
    {
        $this->platform_id = $platform_id;

        return $this;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(?string $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getBrowser(): ?string
    {
        return $this->browser;
    }

    public function setBrowser(string $browser): self
    {
        $this->browser = $browser;

        return $this;
    }
}
