<?php
namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Installer
{
    public function __get($property) {
        $methodName = "get".ucfirst($property);
        if (method_exists($this, $methodName)) {
           return call_user_func(array($this, $methodName));
        } elseif (isset($this->{$property})) {
            return $this->{$property};
        }
        return null;
    }
    public function __set($property, $value) {
        $methodName = "set".ucfirst($property);
        if (method_exists($this, $methodName)) {
            call_user_func_array(array($this,$methodName), array($value));
        } else {
            $this->{$property} = $value;
        }
    }
    private $serverUrl;
    private $websocketUrl;
    private $databaseUrl;
    private $databaseHost;
    private $databaseUser;
    private $databasePassword;
public function __construct(
     $serverUrl,
     $websocketUrl,
      $databaseHost,
      $databaseUser,
      $databasePassword,
      $databaseUrl,
    ) {
        $this->serverUrl = $serverUrl;
        $this->websocketUrl = $websocketUrl;
        $this->databaseHost = $databaseHost;
        $this->databaseUser = $databaseUser;
        $this->databasePassword = $databasePassword;
        $this->databaseUrl = $databaseUrl;

}
public function setServerUrl($val) {
    $this->serverUrl = $val;
}
public function setWebsocketUrl($val) {
    $this->websocketUrl = $val;
}
public function setDatabaseUrl($val) {
    $this->databaseUrl = $val;
}
public function setDatabaseHost($val) {
    $this->databaseHost = $val;
}
public function setDatabaseUser($val) {
    $this->databaseUser = $val;
}
public function setDatabasePassword($val) {
    $this->databasePassword = $val;
}
public function getDatabaseUrl() {
    return $this->databaseUrl;
}
public function getServerUrl() {
    return $this->serverUrl;
}
public function getWebsocketUrl() {
    return $this->websocketUrl;
}
public function getDatabaseHost() {
    return $this->databaseHost;
}
public function getDatabaseUser() {
    return $this->databaseUser;
}
public function getDatabasePassword() {
    return $this->databasePassword;
}
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('serverUrl', new Assert\Url([
            'protocols' => ['http', 'https',],
        ]));
        $metadata->addPropertyConstraint('websocketUrl', new Assert\Url([
            'protocols' => ['ws', 'wss',],
        ]));
        $metadata->addPropertyConstraint('databaseUrl', new Assert\Url([
            'protocols' => ['mysql', 'postgresql','sqlite',],
        ]));
    }
}