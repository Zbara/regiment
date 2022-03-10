<?php

namespace App\Entity;

use App\Repository\AdsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdsRepository::class)]
class Ads
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'ads')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'text', length: 100)]
    #[
        Assert\NotBlank(message: 'Введите сообщение'),
        Assert\Length(
            min: 10,
            max: 100
        )
    ]
    private $messages;

    #[ORM\Column(type: 'integer')]
    private $created;

    #[ORM\Column(type: 'string', length: 255)]

    #[Assert\Regex(
        pattern: "#^https?\:\/\/(?:www\.)?vk.com\/#",
        message: "Ссылка должна вести на Вконтакте."
    )]
    private $redirect;

    #[ORM\Column(type: 'integer')]
    private $count = 0;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $last_time = 0;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'integer')]
    private $views = 0;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    public function __construct(){
        $this->created = time();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser( $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMessages(): ?string
    {
        return $this->messages;
    }

    public function setMessages(string $messages): self
    {
        $this->messages = $messages;

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

    public function getRedirect(): ?string
    {
        return $this->redirect;
    }

    public function setRedirect(string $redirect): self
    {
        $this->redirect = $redirect;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getLastTime(): ?int
    {
        return $this->last_time;
    }

    public function setLastTime(?int $last_time): self
    {
        $this->last_time = $last_time;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
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
}
