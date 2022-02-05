<?php
namespace App\Api\RestfulJsonApi;
use App\Entity\User;

class EntityCollection {
    private $entities = [
        'User' => User::class
    ];
    public function getEntity(string $className):string {
        return $this->entities[$className];
    }
}