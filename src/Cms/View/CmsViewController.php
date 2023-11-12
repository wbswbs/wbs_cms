<?php

namespace App\Cms\View;

class CmsViewController
{

    public function renderNotFound(string $getMessage)
    {
        echo 'NOT FOUND: '.$getMessage;
        exit;
    }

    public function renderError(string $getMessage, int $int)
    {
        echo 'ERROR: '.$getMessage.' http '.$int;
        exit;
    }
}