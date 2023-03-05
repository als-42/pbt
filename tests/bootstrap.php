<?php declare(strict_types=1);

ini_set('error_reporting', (string)E_ALL);

include __DIR__ . '/../vendor/autoload.php';


/// Database in tests not good idea!!!
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
$dotenv->required([
    'POSTGRES_HOST',
    'POSTGRES_PORT',
    'POSTGRES_DB',
    'POSTGRES_USER',
    'POSTGRES_PASSWORD',
]);
