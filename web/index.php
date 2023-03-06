<?php declare(strict_types=1);

ini_set('error_reporting', (string)E_ALL);

include './../vendor/autoload.php';

use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\Stratigility\Middleware\ErrorHandler;
use Laminas\Stratigility\Middleware\RequestHandlerMiddleware;
use Laminas\Stratigility\MiddlewarePipe;
use Rater\Endpoints\CreditRateLimit\LimitUpdateRequestHandler;
use function Laminas\Stratigility\middleware;
use function Laminas\Stratigility\path;


// maybe good tool for api https://api-tools.getlaminas.org/


// Application and configuration

$app = new MiddlewarePipe();

function requestHandler(string $handlerClassName): RequestHandlerMiddleware
{
    $builder = new ContainerBuilder();
    $builder->useAutowiring(true);
    $builder->useAttributes(false);
    $builder->addDefinitions(require(__DIR__ . '/../config/container.php'));

    try {
        $container = $builder->build();

        if ($container->has($handlerClassName)) {
            return new RequestHandlerMiddleware($container->get($handlerClassName));
        }

        exit('request handler not correctly defined');
    } catch (DependencyException|NotFoundException|Exception $e) {
        exit($e->getMessage());
    }
}

$app->pipe(path('/creditRateLimit', requestHandler(
    LimitUpdateRequestHandler::class)));

$app->pipe(middleware(function ($req, $handler) {
    if (!in_array($req->getUri()->getPath(), ['/', ''], true)) {
        return $handler->handle($req);
    }

    $response = new Response();
    $response->getBody()->write('Hello world!');

    return $response;
}));


$app->pipe(path('/foo', middleware(function ($req, $handler) {
    $response = new Response();
    $response->getBody()->write('FOO!');

    return $response;
})));

// 404 handler
$app->pipe(new ErrorHandler(function () {
    return new Response("Error", 404);
}));

$server = new RequestHandlerRunner(
    $app,
    new SapiEmitter(),
    static function () {
        return ServerRequestFactory::fromGlobals();
    },
    static function (\Throwable $e) {
        $response = (new ResponseFactory())->createResponse(500);
        $response->getBody()->write(sprintf(
            'An error occurred: %s',
            $e->getMessage()
        ));
        return $response;
    }
);

$server->run();


/*
/// Setup DI container
$container = new League\Container\Container;

$container->inflector(LoggerAwareInterface::class)
    ->invokeMethod('setLogger', [LoggerAware::class]);

$container->add(\PDO::class)
    ->addArgument(
        sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
            getenv('POSTGRES_HOST'),
            getenv('POSTGRES_PORT'),
            getenv('POSTGRES_DB'),
            getenv('POSTGRES_USER'),
            getenv('POSTGRES_PASSWORD')
        )
    )
    ->setShared(true);

$container->add(CreditLimitApiHandler::class)
    ->addArgument(\PDO::class);

/// Setup App Router
$router = new Router();

$jsonStrategy = new JsonStrategy(new ResponseFactory());
$jsonStrategy->setContainer($container);
$router->setStrategy($jsonStrategy);

$helloApiHandler = function(ServerRequestInterface $request, $logger):ResponseInterface {
    var_dump($logger);

    return new JsonResponse([
        'data' => 'Hello, World!',
        'version' => '0.0.1',
        'message' => 'Rater Service await a POST request.',
    ], 200);
};

$router->get('/', $helloApiHandler);
$router->post('/', CreditLimitApiHandler::class);

*/

