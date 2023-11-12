<?php

namespace App\Routing;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use wbs\Framework\Wbs;

class RequestController extends \wbs\Framework\WbsClass
{
    private $context;
    private $request;
    private $routes;
    private ?array $free_routings = null;

    public function __construct(Wbs $wbs)
    {
        parent::__construct($wbs);
        //important for cronjobs
        //they are not set and throws Exception
        if(!isset($_SERVER['REQUEST_URI']) || is_null($_SERVER['REQUEST_URI'])){
            $_SERVER['REQUEST_URI'] = '';
        }

        $this->context = new RequestContext($_SERVER['REQUEST_URI']);
        $this->request = Request::createFromGlobals();
    }

    public function getFreeRoutings(){
        if(is_null($this->free_routings)){
            $this->free_routings = [
                'check_login',
                'logout',
                'login',
                'reset_password',
                'redirect',
                'save_password'
            ];
        }
        return $this->free_routings;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getMatcher(): UrlMatcher
    {
        return new UrlMatcher($this->getRoutes(), $this->getContext());
    }

    public function getContext(): RequestContext
    {
        return $this->context->fromRequest($this->request);
    }

    public function getRoutes(): RouteCollection
    {
        if (is_null($this->routes)) {
            $fileLocator = new FileLocator([__DIR__]);
            $loader = new ApcCachedYamlFileLoader($fileLocator);
//            $loader = new YamlFileLoader($fileLocator);
            $this->routes = $loader->loader(
                $this->wbs(),
                $this->wbs()->getConfigPath() . 'routes.yaml',
                '');
        }

        return $this->routes;
    }

    /**
     * Liste der Controller aus den Routings extrahieren
     *
     * Array Format für Data Tables Options
     *
     * return [
     *  ['value' => 'Backup', 'label' => 'Backup'],
     *  ['value' => 'Dashboard', 'label' => 'Dashboard']
     * ];
     *
     * @param $add_path
     * @return array
     */
    public function getControllerList($add_path = false): array
    {

//        $this->request()->getRoutes()->getIterator()->getArrayCopy()
        $list = [];
        foreach ($this->getRoutes()->all() as $route_name => $route) {
            $path = $route->getPath();
            $dummy_option = '';
            if ($route->hasOption('dummy_option')) {
                $dummy_option = $route->getOption('dummy_option');
            };
            $path_short = trim($path, '/');
            $snippets = explode('/', $path_short);
            $controller = $snippets[0];
            if (!$controller) {
                $controller = 'dashboard';
            }
            $controller_name = str_replace(' ', '', ucwords(str_replace('_', ' ', $controller)));

            $entry = [
                'value' => $controller,
                'label' => $controller
            ];
            if ($add_path) {
                $entry['path'] = $path;
                $entry['name'] = $route_name;
                $entry['dummy_option'] = $dummy_option;
            }
            // Using Name to sort
            $list[$controller] = $entry;
        }
        ksort($list);
        // Return without the Keys
        return array_values($list);
    }


    public function getExtension()
    {
        return strtolower(pathinfo($this->getRequest()->getPathInfo(), PATHINFO_EXTENSION));
    }

    public static function getHeader()
    {
        $arh = array();
        $rx_http = '/\AHTTP_/';
        foreach($_SERVER as $key => $val) {
            if( preg_match($rx_http, $key) ) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', $arh_key);
                if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
                    foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }
        return( $arh );
    }

}