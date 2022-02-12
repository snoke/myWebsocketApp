<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\JwtSubscriberApi;
abstract class Entity
{
    abstract public function getId();

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
}
