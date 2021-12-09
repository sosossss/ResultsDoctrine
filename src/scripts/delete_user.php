<?php

/**
 * src/scripts/delete_user.php
 *
 * @category Scripts
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

Utils::loadEnv(dirname(__DIR__, 2));

if ($argc != 2) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <userId>

MARCA_FIN;
    exit(0);
}

$userId       = (int) $argv[1];

$entityManager = DoctrineConnector::getEntityManager();

$usersRepository = $entityManager->getRepository(User::class);
$user = $usersRepository->find($userId);

$resultsRepository = $entityManager->getRepository(Result::class);
$results = $resultsRepository->findBy(array('user_id' => $userId));

try {
    foreach ($results as $result) {
        $entityManager->remove($result);
    }
    $entityManager->remove($user);
    $entityManager->flush();
    echo 'Deleted User with ID ' .$userId;
} catch (Throwable $exception) {
    echo $exception->getMessage();
}