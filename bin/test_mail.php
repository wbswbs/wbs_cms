<?php

/**
 * Test Script to check the Credentials and Connection to the Google Calendar API
 */
require __DIR__ . '/../vendor/autoload.php';

if (php_sapi_name() !== 'cli') {
    throw new Exception('This application must be run on the command line.');
}

$wbs = new \wbs\Framework\Wbs(
    dirname(__DIR__) . '/'
);

try {

    $recipient = 'kiba778391@blessen.de';
    $title = 'Eine Testmail aus dem Bashscript ' . __FILE__;
    $content = 'Der Aufruf erfolgte im Verzeichnis ' . __DIR__;
    $wbs->smtp()->sendMailToSiteAdmin($title, $content);

} catch (Exception $e) {
    die('Exception: ' . $e->getMessage() . $e->getTraceAsString());
}
