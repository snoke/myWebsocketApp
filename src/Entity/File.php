<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Entity;

use App\Repository\FileRepository;
use App\Server\JwtSubscriberServer\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 */
#[ORM\Entity(repositoryClass: FileRepository::class)]
class File extends Entity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['app_chat', 'app_chat_send'])]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['app_chat', 'app_chat_send'])]
    private ?string $filename;

    #[ORM\Column(type: 'text')]
    #[Groups(['app_chat', 'app_chat_send'])]
    private ?string $content;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     * @return $this
     */
    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     * @return $this
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
