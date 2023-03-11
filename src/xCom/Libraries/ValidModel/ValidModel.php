<?php

namespace xCom\Libraries\ValidModel;

use xCom\Contracts\ValidatableContract;
use ReflectionObject;

#[\Attribute(\Attribute::TARGET_CLASS|\Attribute::TARGET_PROPERTY|\Attribute::TARGET_PARAMETER)]
class ValidModel implements ValidatableContract
{

    use ValidatableTrait;

    private object $modelInstance;

    public function __construct()
    {
        $this->setUpErrorsContainer();
    }

    public function runValidation(object $modelInstance)
    {
        $this->modelInstance = $modelInstance;

        $reflect = new ReflectionObject($this->modelInstance);

        ##foreach ($reflect->)
    }

    public function addError(\Error $error)
    {
        $this->errorsContainer->push($error);
    }
}