<?php

namespace App\Cms;

use App\Cms\View\CmsViewController;
use App\Routing\RequestController;
use Exception;
use SmartyException;
use Symfony\Component\Routing\RouteCollection;
use wbs\Framework\Config\ENV;
use wbs\Framework\Wbs;
use wbs\Framework\WbsClass;

class CmsController extends WbsClass
{

    // Konstanten fürs Routing
    public const MATCH_SEITE = 'seite';

    // Konstanten für die ENV
    public const ENV_CMS_START_SEITE      = 'CMS_START_SEITE';
    public const ENV_CMS_TEMPLATE         = 'CMS_TEMPLATE';
    public const ENV_CMS_SMARTY_EXTENSION = 'CMS_SMARTY_EXTENSION';

    protected string             $template         = '';
    protected string             $cms_data_path    = '';
    protected string             $smarty_extension = '.html.tpl';
    protected RouteCollection    $routing;
    protected ?RequestController $request          = null;
    protected ?CmsViewController $view             = null;

    public function __construct(Wbs $wbs)
    {
        parent::__construct($wbs);
        $wbs->setRootPath($wbs->env(ENV::ROOT_PATH));
        $this->cms_data_path    = $this->wbs()->getDataPath() . 'cms/';
        $this->template         = $wbs->env(self::ENV_CMS_TEMPLATE, 'default');
        $this->smarty_extension = $wbs->env(self::ENV_CMS_SMARTY_EXTENSION, '.html.tpl');
    }

    /**
     * @throws SmartyException
     */
    public function index()
    {
        //CMS Start Seite
        $this->seite([
                         self::MATCH_SEITE => $this->wbs()->env(self::ENV_CMS_START_SEITE, 'index')
                     ]);
    }

    /**
     * @throws SmartyException
     * @throws Exception
     */
    public function seite($match)
    {
        $debug      = var_export($match, true);
        $seite      = $this->wbs()->getArrayValue(self::MATCH_SEITE, $match, 'index');
        $seite_path = $this->getSeitePath($seite);
        if (!file_exists($seite_path)) {
            $this->view()->renderNotFound($seite_path);
        }

        echo $this->wbs()->smarty()->fetchAbsoluteTemplate(
            $seite_path,
            [
                'title'    => strtoupper($seite), //$this->wbs()->env(ENV::PROJECT_NAME),
                'seite'    => $seite,
                'content'  => $this->wbs()->html()->h1('Seitenhandler'),#
                'projekt'  => $this->wbs()->env(ENV::PROJECT_NAME),
                'template' => $this->template, //$this->wbs()->env(ENV::PROJECT_NAME),
                'debug'    => $debug
            ]
        );
        exit;
    }

    public function getSeitePath(string $seite): string
    {
        return $this->getCmsDataPath() . 'seite/' . $seite . $this->getSmartyExtension();
    }

    public function getInhaltPath(string $inhalt): string
    {
        return $this->getCmsDataPath() . 'inhalt/' . $inhalt . $this->getSmartyExtension();
    }



    /**************************************************************************
     * S U B C L A S S E S
     *************************************************************************/
    /**
     * @return CmsViewController
     */
    public function view(): CmsViewController
    {
        if (is_null($this->view)) {
            return new CmsViewController();
        }
        return $this->view;
    }

    /**************************************************************************
     * G E T T E R / S E T T E R
     *************************************************************************/
    /**
     * @return string
     */
    public function getCmsDataPath(): string
    {
        return $this->cms_data_path;
    }

    /**
     * @param string $cms_data_path
     */
    public function setCmsDataPath(string $cms_data_path): void
    {
        $this->cms_data_path = $cms_data_path;
    }

    public function getSmartyExtension(): string
    {
        return $this->smarty_extension;
    }

    public function setSmartyExtension(string $smarty_extension): void
    {
        $this->smarty_extension = $smarty_extension;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }


}