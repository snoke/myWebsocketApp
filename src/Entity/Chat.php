<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Api\JwtSubscriberApi\Entity;
#[ORM\Entity(repositoryClass: ChatRepository::class)]
class Chat extends Entity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['app_chat','app_user_chats','chat_message_status'])]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'chats')]
    #[Groups(['app_chat','app_user_chats'])]
    private $users;
    
    #[ORM\OneToMany(mappedBy: 'chat', targetEntity: ChatMessage::class)]
    private $chatMessages;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['app_chat','app_user_chats'])]
    private $blockedBy;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['app_chat','app_user_chats'])]
    private $typing;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->chatMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection|ChatMessage[]
     */
    public function getChatMessages(): Collection
    {
        return $this->chatMessages;
    }
    public function setChatMessages($chatMessages): self
    {
            $this->chatMessages[] = $chatMessages;
            return $this;
    }

    public function addChatMessage(ChatMessage $chatMessage): self
    {
        if (!$this->chatMessages->contains($chatMessage)) {
            $this->chatMessages[] = $chatMessage;
            $chatMessage->setChat($this);
        }

        return $this;
    }

    public function removeChatMessage(ChatMessage $chatMessage): self
    {
        if ($this->chatMessages->removeElement($chatMessage)) {
            // set the owning side to null (unless already changed)
            if ($chatMessage->getChat() === $this) {
                $chatMessage->setChat(null);
            }
        }

        return $this;
    }

    public function getBlockedBy(): ?User
    {
        return $this->blockedBy;
    }

    public function setBlockedBy(?User $blockedBy): self
    {
        $this->blockedBy = $blockedBy;

        return $this;
    }

    public function getTyping(): ?\DateTimeInterface
    {
        return $this->typing;
    }

    public function setTyping(?\DateTimeInterface $typing): self
    {
        $this->typing = $typing;

        return $this;
    }
}
