<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use App\Server\JwtSubscriberServer\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(normalizationContext: ['groups' => ['user']])]
class User extends Entity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user', 'app:user:chats', 'app:chat', 'app:chat:send', 'app:user:search', 'app:user:contacts', 'chat:message:status', 'chat:load:messages'])]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['user', 'app:user:chats', 'app:chat', 'app:chat:send', 'app:user:search', 'app:user:contacts', 'chat:message:status'])]
    private ?string $username;

    #[ORM\Column(type: 'json')]
    #[Groups('user')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private ?string $password;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'contacts')]
    private Collection $contacts;

    #[ORM\ManyToMany(targetEntity: Chat::class, mappedBy: 'users')]
    private Collection $chats;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->chats = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
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

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param string $role
     * @return $this
     */
    public function addRole(string $role): self
    {
        $this->roles = array_unique((array)$this->roles[] = $role);

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
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

    /**
     * @return Collection
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    /**
     * @param User $contact
     * @return $this
     */
    public function addContact(self $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
        }

        return $this;
    }

    /**
     * @param User $contact
     * @return $this
     */
    public function removeContact(self $contact): self
    {
        $this->contacts->removeElement($contact);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    /**
     * @param Chat $chat
     * @return $this
     */
    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->addUser($this);
        }

        return $this;
    }

    /**
     * @param Chat $chat
     * @return $this
     */
    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            $chat->removeUser($this);
        }

        return $this;
    }


}
