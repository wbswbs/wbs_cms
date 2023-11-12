<?php
namespace App\Routing;

use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;
use wbs\Framework\Wbs;

/**
 * ApcCachedYamlFileLoader loads and cachesYaml routing files.
 *
 */
class ApcCachedYamlFileLoader extends YamlFileLoader
{

    private $apc_cache= false;

    /**
     * Nutzt den APC Cache für dem YAML Fileloader.
     * Überprüfe ob APC aktiviert ist auf dem Server, ansonsten nutze normalen Loader.
     *
     * Magic happens in parent, added just caching layer
     *
     * @param string $file A Yaml file path
     * @param string $type The resource type
     *
     * @return RouteCollection A RouteCollection instance
     *
     * @throws \InvalidArgumentException When route can't be parsed
     *
     * @api
     */
    public function load(mixed $file, string $type = null): RouteCollection
    {

        if(function_exists('apcu_fetch') && function_exists('apcu_store') && $this->apc_cache) {
            // Key in cahe is routes_filepath_type
            $key = 'routes_' . $file . $type;
            // Checking if collection is in cache
            if ($collection = apcu_fetch($key)) {
                return $collection; // Returning cached collection
            }

            // Loading collection with YamlFileLoader (parent) and caching it
            $collection = parent::load($file, $type);
            apcu_store($key, $collection);

            return $collection;
        }else{
            return parent::load($file, $type = null);
        }
    }

    /**
     * checks is apccache is enabled in .env
     * @param $file
     * @param $type
     * @return RouteCollection
     */
    public function loader(Wbs $wbs,$file,$type=null)
    {
        $apcCache = 0; //(bool)$wbs->env(ENV::ENV_APC_CACHE,0);
        if($apcCache)
        {
            $this->apc_cache = true;

        }

        return $this->load($file, $type);

    }
}