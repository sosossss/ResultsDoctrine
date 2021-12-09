<?php

/**
 * src/scripts/modify_result.php
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

if ($argc != 3) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <resultId> <result>

MARCA_FIN;
    exit(0);
}

$resultId       = (int) $argv[1];
$resultValue       = (int) $argv[2];

$entityManager = DoctrineConnector::getEntityManager();

$resultsRepository = $entityManager->getRepository(Result::class);
$result = $resultsRepository->find($resultId);
$result->setResult($resultValue);

try {
    $entityManager->persist($result);
    $entityManager->flush();
    echo 'Modified Result with ID ' . $result->getId();
} catch (Throwable $exception) {
    echo $exception->getMessage();
}



