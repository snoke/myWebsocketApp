<?php
namespace App\Api\RestfulApi;

use App\Api\JsonApi\CommandCollection as Base;

use App\Api\RestfulJsonApi\Command\GetCommand;
use App\Api\RestfulJsonApi\Command\PostCommand;
use App\Api\RestfulJsonApi\Command\PutCommand;
use App\Api\RestfulJsonApi\Command\PatchCommand;
use App\Api\RestfulJsonApi\Command\DeleteCommand;
use App\Api\RestfulJsonApi\Command\HeadCommand;

class CommandCollection extends Base {
    
    public function __construct(
        GetCommand $getCommand,
        PostCommand $postCommand,
        PutCommand $putCommand,
        PatchCommand $patchCommand,
        DeleteCommand $deleteCommand,
        HeadCommand $headCommand,
        ) {
            parent::__construct();
            $this->addCommand($getCommand);
            $this->addCommand($postCommand);
            $this->addCommand($putCommand);
            $this->addCommand($patchCommand);
            $this->addCommand($deleteCommand);
            $this->addCommand($headCommand);
    }
}