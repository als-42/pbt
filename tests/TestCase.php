<?php

namespace Tests;

use DI\ContainerBuilder;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

class TestCase extends \PHPUnit\Framework\TestCase
{

    protected LoggerInterface $logger;
    protected ContainerInterface $container;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $builder = new ContainerBuilder();
        $builder->useAutowiring(false);
        $builder->useAttributes(false);
        $builder->addDefinitions(require(__DIR__ . '/../config/container.php'));

        try {
            $this->container = $builder->build();

            $this->logger = $this->container->get(LoggerInterface::class);

        } catch (NotFoundExceptionInterface|ContainerExceptionInterface|Exception $e) {
            $this->logger->critical($e->getMessage());
            exit('container build failure: '. $e->getMessage());
        }
    }
}