<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string')]
    private int $vkontakteID;

    #[ORM\Column(type: 'integer')]
    private $updateTime;

    #[ORM\Column(type: 'string', length: 255)]
    private $access_token;

    #[ORM\Column(type: 'string', length: 255)]
    private $first_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $last_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $screen_name;

    #[ORM\Column(type: 'string', length: 255)]
    private $photo_medium;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Ads::class)]
    private $ads;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: UsersToken::class, cascade: ['persist', 'remove'])]
    private $usersToken;

    public function __construct()
    {
        $this->ads = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return int
     */
    public function getVkontakteID(): int
    {
        return $this->vkontakteID;
    }

    /**
     * @param mixed $vkontakteID
     */
    public function setVkontakteID(int $vkontakteID): self
    {
        $this->vkontakteID = $vkontakteID;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }


    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    public function setAccessToken(string $access_token): self
    {
        $this->access_token = $access_token;

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

    public function getScreenName(): ?string
    {
        return $this->screen_name;
    }

    public function setScreenName(string $screen_name): self
    {
        $this->screen_name = $screen_name;

        return $this;
    }

    public function getPhotoMedium(): ?string
    {
        return $this->photo_medium;
    }

    public function setPhotoMedium(string $photo_medium): self
    {
        $this->photo_medium = $photo_medium;

        return $this;
    }

    /**
     * @return Collection|Ads[]
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ads $ad): self
    {
        if (!$this->ads->contains($ad)) {
            $this->ads[] = $ad;
            $ad->setUser($this);
        }

        return $this;
    }

    public function removeAd(Ads $ad): self
    {
        if ($this->ads->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getUser() === $this) {
                $ad->setUser(null);
            }
        }

        return $this;
    }

    public function getUsersToken(): ?UsersToken
    {
        return $this->usersToken;
    }

    public function setUsersToken(?UsersToken $usersToken): self
    {
        // unset the owning side of the relation if necessary
        if ($usersToken === null && $this->usersToken !== null) {
            $this->usersToken->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($usersToken !== null && $usersToken->getUser() !== $this) {
            $usersToken->setUser($this);
        }

        $this->usersToken = $usersToken;

        return $this;
    }
}
