<?php

namespace App\Entity;

use App\Repository\ChatMessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ChatMessageRepository::class)]
class ChatMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['app_chat','app_chat_send'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy: 'chatMessages')]
    private $chat;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['app_chat','app_chat_send'])]
    private $sender;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['app_chat','app_chat_send'])]
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(?Chat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
