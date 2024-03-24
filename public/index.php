<?php
use wbs\Framework\Config\ENV;
use wbs\Framework\Ip\Ip;

require_once "../vendor/autoload.php";

$Routing = null;

try {
    # Loading and Debugging WBS FW
    $wbs = new wbs\Framework\Wbs('../');
    $out = $wbs->html()->h2('Wbs Framework loaded');
    $out .= 'Data Path: ' . $wbs->getDataPath() . PHP_EOL;
    $out .= 'Project Name(ENV): ' . $wbs->env(ENV::PROJECT_NAME) . PHP_EOL;
    $wbs->log()->info('Call index.php von ' . Ip::calculateIP());
    $out .= 'Log: Look at var/log/' . PHP_EOL;
    $out .= 'File: ' . __FILE__ . PHP_EOL;
    $out .= 'DONE: Smarty Integration' . PHP_EOL;
    $out .= 'Next: Symfony Routing' . PHP_EOL;


    $content = nl2br($out);

    $Routing = new \App\Routing\RoutingController($wbs);
    $Routing->render();

} catch (Exception | Error $e) {
    if (
        $Routing && $Routing instanceof \App\Routing\RoutingController) {
//        $Routing->view()->renderGuestError($e->getMessage());
        $Routing->view()->renderError($e->getMessage(), 400);
    } else {
        echo 'EXCEPTION: ' . $e->getMessage();
        echo $e->getTraceAsString();
    }

    exit (0);
}