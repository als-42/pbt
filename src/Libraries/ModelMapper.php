<?php

namespace XCom\Libraries;


use ReflectionException;
use ValueError;
use XCom\Libraries\ValidModel\ErrorsContainer;
use XCom\Libraries\ValidModel\Required;
use XCom\Libraries\ValidModel\ValidatableTrait;
use XCom\Libraries\ValidModel\ValidModel;

class ModelMapper
{
    use ValidatableTrait;
    private static ModelMapper $self;
    private static int $depth = 0;

    private object $tree;
    private array $requiredParams = [];
    // validatable trait provide private ErrorsContainer $errorsContainer;
    public static function Map(object $source, string $target): object|null
    {

        if (static::$depth < 1) {
            static::$depth = 1;
            static::$self = new self();
            $self = self::$self;
            $self->tree = new \stdClass();
            $self->errorsContainer = new ErrorsContainer();
        }

        $self = self::$self;

        $tmp = $self->scanForRequiredFields($source, $target);

        // early exit or maybe better throw Value Error Exception?
        if ($self->errorsContainer->hasErrors()) return $self;

        $tmp1 = $self->deepInstanceInvocation($tmp, $source, $target);

        // not sure about right solution:
        // is like copy validation error closed to model
        // where it is used?
        $tmp1->instance->injectErrorContainer($self->errorsContainer);

        return $tmp1->instance;
    }

    private function deepInstanceInvocation(object $tmp, object $source, string $target): object|null
    {
        foreach (get_object_vars($tmp->params) as $param){

            if (property_exists($param, 'className'))
                $this->deepInstanceInvocation($param, $source, $target);
        }

        try {
            if ($tmp->value !== null) {
                $tmp->instance = new $tmp->className($tmp->value);
            } else {
                $values = [];
                foreach (get_object_vars($tmp->params) as $param) {
                    if ($param->value !== null and !$param->instance) {
                        $values[] = $param->value;
                    } else if ($param->instance) {
                        $values[] = $param->instance;
                    } else {
                        $values[] = null;
                        //$this->errorsContainer ->push(new ValueError('no value'));
                    }
                }

                $tmp->instance = new $tmp->className(...$values);
            }

            // not have time for cool type system validation
        } catch (\TypeError|\Error $error){
            $this->errorsContainer->push(new ValueError(sprintf(
                "Value error: %s, %s ",
                $tmp->name, var_export($tmp->value, true)
            )));
        }

        return $tmp;
    }
    /**
     *
     * tree [stdClass]
     * -> CreditRequest:class
     * ----> params
     * --------> uuid:class
     * ------------ params
     * --------> Client:class
     * -------------params
     * ->
     *
     *
     *
     * @param object $source
     * @param string $target
     * @return object|null
     */

    private function scanForRequiredFields(object $source, string $target): object|null
    {
        try {
            $reflect = new \ReflectionClass($target);

            $constructor = $reflect->getConstructor();
            if (!$constructor) return null; // no construct - no deps

            $firstLevel = new \stdClass();
            $firstLevel->className = $target;

            $firstLevel->name = $firstLevel->className;

            $firstLevel->recursive = null; // not working
            $firstLevel->instance = null;// not working
            $firstLevel->value = null;

            $firstLevel->params = new \stdClass();
            foreach ($constructor->getParameters() as $parameter)
            {
                if(!$parameter->hasType())
                    $this->errorsContainer
                        ->push(new ValueError('Param should has type'));

                $param = new \stdClass();
                $param->name = $parameter->name;
                $param->required = null;
                $param->recursive = true;
                $param->value = null;
                $param->instance = null;
                $param->type = 'class';


                if (!$parameter->getType()->allowsNull() and !$parameter->getType()->isBuiltin())
                {
                    $param = $this->scanForRequiredFields($source, $parameter->getType()->getName());
                    $param->name = $parameter->name;
                    $param->required = null;
                    $param->recursive = true;
                    $param->value = null;
                    $param->instance = null;
                    $param->type = 'class';
                } else {
                    $param->type = $parameter->getType()->getName();
                }

                /* not working */
                foreach ($parameter->getAttributes() as $attribute) {
                    if ($attribute->getName() == Required::class and $attribute->getName() != ValidModel::class)
                        $param->required = true;
                }

                if (property_exists($source, $parameter->getName())){
                    $param->required = false; // logic mutation true become false
                    $param->value = $source->{$parameter->getName()};
                } else if ($param->required) {
                    $this->errorsContainer->push(
                        new ValueError(sprintf('Param %s is required', $param->name))
                    );

                }
                $firstLevel->params->{$parameter->getName()} = $param;


            }
        } catch (ReflectionException $exception){
            $this->errorsContainer->push($exception->getMessage());
            exit($exception->getMessage());
        }

        return $firstLevel;

    }

    /***
     *
     *
     * First messy version of recursive data mapper
     *
     * @deprecated
     *
     * @param object $source
     * @param string $target
     * @return object|null
     * @throws ReflectionException
     *
     */
    private function collectDependsFromConstructor(object $source, string $target): object|null
    {
        $reflect = new \ReflectionClass($target);

        $constructor = $reflect->getConstructor();
        if (!$constructor) return null; // no construct - no deps

        $firstLevel = new \stdClass();
        $firstLevel->depends = [];

        foreach ($constructor->getParameters() as $parameter) {
            /** @var \ReflectionNamedType| \ReflectionUnionType $type */
            if ($type = $parameter->getType()) {
                if ($type->isBuiltin()) {
                    //continue;
                } else {
                    $dep = new \stdClass();
                    $dep->typeName = $type->getName();
                    $dep->paramName = $parameter->getName();
                    $firstLevel->depends[$dep->paramName] = $dep;
                }
            } else {
                $firstLevel->validModelInstance->addError(
                    new \TypeError('require typed params in constructor')
                );
            }
        }

        $this->tree->{$reflect->name} = $firstLevel;


        foreach ($firstLevel->depends as &$depend) {
            $depend->resolve = ModelMapper::Map($source, $depend->typeName);
        }

        // not production hack, require long time for fix it
        if ($reflect->name == \DateTimeZone::class)
            $source->timezone = 'Europe/London';
        if ($reflect->name == \DateTime::class)
            $source->datetime = (new \DateTime())->format('Y-d-m');

        $paramsValues = [];
        $probablyError = null;
        foreach ($constructor->getParameters() as $param)
        {
            $paramsValues[$param->getName()] = null;

            $probablyError = new ValueError(
                sprintf( 'Param %s, is required', $param->getName())
            );

            if (property_exists($source, $param->getName())){
                $paramsValues[$param->getName()] = $source->{$param->getName()};
                $probablyError = null;
            }

            if (array_key_exists($param->getName(), $this->tree->{$reflect->name}->depends)){
                $paramsValues[$param->getName()] = $this->tree->{$reflect->name}->depends[$param->getName()]->resolve;
                $probablyError = null;
            }
        }

        $instance = $reflect->newInstanceArgs($paramsValues);

        $this->tree->{$reflect->name} =  $instance;

        return $instance;

    }
}