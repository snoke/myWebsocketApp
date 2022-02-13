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
public function __construct(
     $serverUrl,
     $websocketUrl,
     $databaseUrl,
    ) {
        $this->serverUrl = $serverUrl;
        $this->websocketUrl = $websocketUrl;
        $this->databaseUrl = $databaseUrl;

}
public function setServerUrl($url) {
    $this->serverUrl = $url;
}
public function setWebsocketUrl($url) {
    $this->websocketUrl = $url;
}
public function setDatabaseUrl($url) {
    $this->databaseUrl = $url;
}
public function getServerUrl() {
    return $this->serverUrl;
}
public function getWebsocketUrl() {
    return $this->websocketUrl;
}
public function getDatabaseUrl() {
    return $this->databaseUrl;
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