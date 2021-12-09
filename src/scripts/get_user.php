<?php

/**
 * src/scripts/get_user.php
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

echo PHP_EOL . sprintf(
    '  %2s: %20s %30s %7s' . PHP_EOL,
    'Id', 'Username:', 'Email:', 'Enabled:'
);
$items = 0;
echo sprintf(
    '- %2d: %20s %30s %7s',
    $user->getId(),
    $user->getUsername(),
    $user->getEmail(),
    ($user->isEnabled()) ? 'true' : 'false'
),
$items++;

