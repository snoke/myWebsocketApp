<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Entity;

use App\Repository\ChatMessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Api\JwtSubscriberApi\Entity;
#[ORM\Entity(repositoryClass: ChatMessageRepository::class)]
class ChatMessage extends Entity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['app_chat','app_chat_send','chat_message_status','chat:load:messages'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy: 'chatMessages')]
    #[Groups(['chat_message_status'])]
    private $chat;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['app_chat','app_chat_send','chat_message_status','chat:load:messages','chat:load:messages'])]
    private $sender;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['app_chat','app_chat_send','chat_message_status','chat:load:messages'])]
    private $message;

    #[ORM\ManyToOne(targetEntity: File::class)]
    #[Groups(['app_chat','app_chat_send','chat_message_status','chat:load:messages'])]
    private $file;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['app_chat','app_chat_send','chat_message_status','chat:load:messages'])]
    private $sent;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['app_chat','app_chat_send','chat_message_status','chat:load:messages'])]
    private $delivered;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['app_chat','app_chat_send','chat_message_status','chat:load:messages'])]
    private $seen;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['app_chat','app_chat_send','chat_message_status','chat:load:messages'])]
    private $status;

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

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getSent(): ?\DateTimeInterface
    {
        return $this->sent;
    }

    public function setSent(?\DateTimeInterface $sent): self
    {
        $this->sent = $sent;

        return $this;
    }

    public function getDelivered(): ?\DateTimeInterface
    {
        return $this->delivered;
    }

    public function setDelivered(?\DateTimeInterface $delivered): self
    {
        $this->delivered = $delivered;

        return $this;
    }

    public function getSeen(): ?\DateTimeInterface
    {
        return $this->seen;
    }

    public function setSeen(?\DateTimeInterface $seen): self
    {
        $this->seen = $seen;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
