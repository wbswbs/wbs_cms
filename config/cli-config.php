<?php
/**
 * Init Script für Doctrine auf der Kommandozeile
 */

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use wbs\Framework\Config\ENV;

$root_path = realpath(dirname(__DIR__)).'/';
/**
 * Using Composer Autoloading
 */
require $root_path . "vendor/autoload.php";

try {

    $wbs = new \wbs\Framework\Wbs($root_path);

    $_SERVER['APP_ENV'] = $wbs->env(ENV::APP_ENV);
    $_SERVER['APP_DEBUG'] = true;

    $entityManager = $wbs->doctrine()->entity_manger();
    $conn=$entityManager->getConnection();
    $conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

    return ConsoleRunner::createHelperSet($entityManager);

}catch (Exception $e){
    die('Error in '.__FILE__.': '.$e->getMessage().PHP_EOL);
}
