<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 *
 */
class Installer
{

    private ?string $serverUrl;
    private ?string $websocketUrl;
    private ?string $databaseUrl;
    private ?string $databaseHost;
    private ?string $databaseUser;
    private ?string $databasePassword;

    public function __construct(
        ?string $serverUrl,
        ?string $websocketUrl,
        ?string $databaseHost,
        ?string $databaseUser,
        ?string $databasePassword,
        ?string $databaseUrl,
    )
    {
        $this->serverUrl = $serverUrl;
        $this->websocketUrl = $websocketUrl;
        $this->databaseHost = $databaseHost;
        $this->databaseUser = $databaseUser;
        $this->databasePassword = $databasePassword;
        $this->databaseUrl = $databaseUrl;

    }

    public function setServerUrl(?string $val): void
    {
        $this->serverUrl = $val;
    }

    public function setWebsocketUrl(?string $val): void
    {
        $this->websocketUrl = $val;
    }

    public function setDatabaseUrl(?string $val): void
    {
        $this->databaseUrl = $val;
    }

    public function setDatabaseHost(?string $val): void
    {
        $this->databaseHost = $val;
    }

    public function setDatabaseUser(?string $val): void
    {
        $this->databaseUser = $val;
    }

    public function setDatabasePassword(?string $val): void
    {
        $this->databasePassword = $val;
    }

    public function getDatabaseUrl(): ?string
    {
        return $this->databaseUrl;
    }

    public function getServerUrl(): ?string
    {
        return $this->serverUrl;
    }

    public function getWebsocketUrl(): ?string
    {
        return $this->websocketUrl;
    }

    public function getDatabaseHost(): ?string
    {
        return $this->databaseHost;
    }

    public function getDatabaseUser(): ?string
    {
        return $this->databaseUser;
    }

    public function getDatabasePassword(): ?string
    {
        return $this->databasePassword;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('serverUrl', new Assert\Url([
            'protocols' => ['http', 'https',],
        ]));
        $metadata->addPropertyConstraint('websocketUrl', new Assert\Url([
            'protocols' => ['ws', 'wss',],
        ]));
        $metadata->addPropertyConstraint('databaseUrl', new Assert\Url([
            'protocols' => ['mysql', 'postgresql', 'sqlite',],
        ]));
    }

}