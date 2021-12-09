<?php

/**
 * src/scripts/delete_result.php
 *
 * @category Scripts
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

Utils::loadEnv(dirname(__DIR__, 2));

if ($argc != 2) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <resultId>

MARCA_FIN;
    exit(0);
}

$resultId       = (int) $argv[1];

$entityManager = DoctrineConnector::getEntityManager();

$resultsRepository = $entityManager->getRepository(Result::class);
$result = $resultsRepository->find($resultId);

try {
    $entityManager->remove($result);
    $entityManager->flush();
    echo 'Deleted Result with ID ' .$resultId;
} catch (Throwable $exception) {
    echo $exception->getMessage();
}
