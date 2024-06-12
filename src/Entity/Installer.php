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

    /**
     * @param string|null $serverUrl
     * @param string|null $websocketUrl
     * @param string|null $databaseHost
     * @param string|null $databaseUser
     * @param string|null $databasePassword
     * @param string|null $databaseUrl
     */
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

    /**
     * @param string|null $val
     * @return void
     */
    public function setServerUrl(?string $val): void
    {
        $this->serverUrl = $val;
    }

    /**
     * @param string|null $val
     * @return void
     */
    public function setWebsocketUrl(?string $val): void
    {
        $this->websocketUrl = $val;
    }

    /**
     * @param string|null $val
     * @return void
     */
    public function setDatabaseUrl(?string $val): void
    {
        $this->databaseUrl = $val;
    }

    /**
     * @param string|null $val
     * @return void
     */
    public function setDatabaseHost(?string $val): void
    {
        $this->databaseHost = $val;
    }

    /**
     * @param string|null $val
     * @return void
     */
    public function setDatabaseUser(?string $val): void
    {
        $this->databaseUser = $val;
    }

    /**
     * @param string|null $val
     * @return void
     */
    public function setDatabasePassword(?string $val): void
    {
        $this->databasePassword = $val;
    }

    /**
     * @return string|null
     */
    public function getDatabaseUrl(): ?string
    {
        return $this->databaseUrl;
    }

    /**
     * @return string|null
     */
    public function getServerUrl(): ?string
    {
        return $this->serverUrl;
    }

    /**
     * @return string|null
     */
    public function getWebsocketUrl(): ?string
    {
        return $this->websocketUrl;
    }

    /**
     * @return string|null
     */
    public function getDatabaseHost(): ?string
    {
        return $this->databaseHost;
    }

    /**
     * @return string|null
     */
    public function getDatabaseUser(): ?string
    {
        return $this->databaseUser;
    }

    /**
     * @return string|null
     */
    public function getDatabasePassword(): ?string
    {
        return $this->databasePassword;
    }

    /**
     * @param ClassMetadata $metadata
     * @return void
     */
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