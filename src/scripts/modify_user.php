<?php

/**
 * src/scripts/modify_user.php
 *
 * @category Scripts
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

Utils::loadEnv(dirname(__DIR__, 2));

if ($argc != 5) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <userId> <userName> <email> <password>

MARCA_FIN;
    exit(0);
}

$userId       = (int) $argv[1];
$userName       = (string) $argv[2];
$email       = (string) $argv[3];
$password       = (string) $argv[4];

$entityManager = DoctrineConnector::getEntityManager();

$usersRepository = $entityManager->getRepository(User::class);
$user = $usersRepository->find($userId);
$user->setUsername($userName);
$user->setEmail($email);
$user->setPassword($password);

try {
    $entityManager->persist($user);
    $entityManager->flush();
    echo 'Modified User with ID ' . $user->getId();
} catch (Throwable $exception) {
    echo $exception->getMessage();
}




