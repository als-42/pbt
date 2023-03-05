<?php

namespace Rater\Services\ValidModel;

trait ValidatableTrait
{

    protected ErrorsContainer $errorsContainer;


    public function hasErrors(): bool
    {
        return $this->errorsContainer->hasErrors();
    }

    public function getErrors(): array
    {
        // no time for make right solution
        // messy hiding stack trace from output . for test task is enough!
        $errors = $this->errorsContainer->getErrors();
        $messages = array();
        foreach ($errors as $error){
            $messages[] = $error->getMessage();
        }

        return $messages;
    }

    private function setUpErrorsContainer(): void
    {
        $this->errorsContainer = new ErrorsContainer();
    }


    // really stupid, is like save validation error closed to model
    public function injectErrorContainer(ErrorsContainer $errorsContainer): void
    {
        $this->errorsContainer = $errorsContainer;
    }

}