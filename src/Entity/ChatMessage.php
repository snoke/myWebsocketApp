<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Entity;

use App\Repository\ChatMessageRepository;
use App\Server\JwtSubscriberServer\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * ChatMessage
 */
#[ORM\Entity(repositoryClass: ChatMessageRepository::class)]
class ChatMessage extends Entity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['app:chat', 'app:chat:send', 'chat:message:status', 'chat:load:messages'])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy: 'chatMessages')]
    #[Groups(['chat:message:status'])]
    private ?Chat $chat;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['app:chat', 'app:chat:send', 'chat:message:status', 'chat:load:messages', 'chat:load:messages'])]
    private ?User $sender;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['app:chat', 'app:chat:send', 'chat:message:status', 'chat:load:messages'])]
    private ?string $message;

    #[ORM\ManyToOne(targetEntity: File::class)]
    #[Groups(['app:chat', 'app:chat:send', 'chat:message:status', 'chat:load:messages'])]
    private ?File $file;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['app:chat', 'app:chat:send', 'chat:message:status', 'chat:load:messages'])]
    private ?\DateTimeInterface $sent;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['app:chat', 'app:chat:send', 'chat:message:status', 'chat:load:messages'])]
    private ?\DateTimeInterface $delivered;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['app:chat', 'app:chat:send', 'chat:message:status', 'chat:load:messages'])]
    private ?\DateTimeInterface $seen;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['app:chat', 'app:chat:send', 'chat:message:status', 'chat:load:messages'])]
    private ?string $status;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Chat|null
     */
    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    /**
     * @param Chat|null $chat
     * @return $this
     */
    public function setChat(?Chat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getSender(): ?User
    {
        return $this->sender;
    }

    /**
     * @param User|null $sender
     * @return $this
     */
    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     * @return $this
     */
    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getSent(): ?\DateTimeInterface
    {
        return $this->sent;
    }

    /**
     * @param \DateTimeInterface|null $sent
     * @return $this
     */
    public function setSent(?\DateTimeInterface $sent): self
    {
        $this->sent = $sent;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDelivered(): ?\DateTimeInterface
    {
        return $this->delivered;
    }

    /**
     * @param \DateTimeInterface|null $delivered
     * @return $this
     */
    public function setDelivered(?\DateTimeInterface $delivered): self
    {
        $this->delivered = $delivered;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getSeen(): ?\DateTimeInterface
    {
        return $this->seen;
    }

    /**
     * @param \DateTimeInterface|null $seen
     * @return $this
     */
    public function setSeen(?\DateTimeInterface $seen): self
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return $this
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
