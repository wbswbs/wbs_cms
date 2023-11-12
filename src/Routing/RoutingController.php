<?php

namespace App\Routing;

use App\Cms\View\CmsViewController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouteCollection;

class RoutingController extends \wbs\Framework\WbsClass
{
    protected RouteCollection    $routing;
    protected ?RequestController $request = null;
    protected ?CmsViewController $view    = null;
    protected string             $request_path;

    const DEFAULT_ROUTING_ACTION = 'index';
    // bei 404 werden diese nicht über smarty gerendert.
    public const EXT_NOT_RENDERING = ['png', 'pdf', 'jpg'];
    # What to do with 404 Errors. Options:
    # ignore, info (log as info), warning (log as warning), error (log as error)
    public const ENV_HANDLE_404 = 'HANDLE_404';


    /**
     * @return void
     * @throws \SmartyException
     */
    public function render()
    {
        //removes slash at the end
        $this->request_path = rtrim($this->request()->getRequest()->getPathInfo(), "/");

        $this->wbs()->log()->info('[ROUTING] Request: '.$this->request_path);

        $match    = null;
        $response = null;

        try {
            $match = $this->request()->getMatcher()->match($this->request_path);
        } catch (NoConfigurationException $exception) {
            $this->view()->renderNotFound($exception->getMessage());
        } catch (MethodNotAllowedException $exception) {
            $this->view()->renderError(
                "The HTTP Method is not allowed in Routing {$this->request_path}",
                500,
                (array)$this->request_path
            );
        } catch (ResourceNotFoundException $exception) {
            /**************************************************************************
             * bei 404 werden diese nicht über smarty gerendert.
             *************************************************************************/

            if (in_array(
                    $this->request()->getExtension(),
                    self::EXT_NOT_RENDERING
                ) && !empty(self::EXT_NOT_RENDERING)) {
                switch ($this->wbs()->env(self::ENV_HANDLE_404)) {
                    case 'ignore':
                        break;
                    case 'warning':
                        $this->wbs()->log()->warning('404 NOT FOUND: ' . $this->request_path);
                        break;
                    case 'error':
                        $this->wbs()->log()->error('404 NOT FOUND: ' . $this->request_path);
                        break;
                    case 'info':
                    default:
                        $this->wbs()->log()->info('404 NOT FOUND: ' . $this->request_path);
                        break;
                }
                header("HTTP/1.0 404 Not Found");
                die();
            }

            $this->view()->renderNotFound($exception->getMessage());
        }

        /**
         * start session
         */
//        $this->request()->session();

        $free_routings = $this->request()->getFreeRoutings();

        $this->wbs()->log()->info($this->getMitarbeiterName() . ': ' . $this->request_path);

        try {
            $this->wbs()->log()->info("[ROUTING] match " . var_export($match, true));
            if (str_contains($match['_controller'], '::')) {
                list($controller, $action) = explode('::', $match['_controller']);
            } else {
                $controller = $match['_controller'];
                $action     = $this->wbs()->getArrayValue('action', $match, self::DEFAULT_ROUTING_ACTION);
            }
            $this->wbs()->log()->info('[ROUTING] Controller: ' . $controller . ' Action: ' . $action);

            if (method_exists($controller, $action)) {
                $controller = new ($controller)($this->wbs());
                $controller->{$action}($match);
            } else {
                throw new ResourceNotFoundException("Controller Action {$action} nicht gefunden in " . $controller);
            }

            $response = new Response(ob_get_clean());
        } catch (ResourceNotFoundException $exception) {
            $this->view()->renderNotFound($exception->getMessage());
        } catch (\Exception $exception) {
//       TODO     $this->wbs()->smtp()->smtp()->sendMailToSiteAdmin(
//                '[WBS CMS] Error matching',
//                var_export($exception->getMessage(), true)
//            );
            $this->view()->renderError($exception->getMessage(), 500);
        }
        $response->send();
    }

    /**
     * @return string
     */
    public function getRequestPath(): string
    {
        return rtrim($this->request()->getRequest()->getPathInfo(), "/");
    }

    /**
     * IP
     *
     * @return string|void
     * @throws \Exception
     */
    public function getMitarbeiterName()
    {
        return $this->wbs()->ip()::calculateIP();
    }

    /**
     * Pfad zum src Ordner
     *
     * @return string
     */
    public function getPath()
    {
        return $this->wbs()->getRootPath() . 'src/';
    }


    /**************************************************************************
     * S H O R T C U T S (A - Z)
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

    /**
     * Shortcut to system -> request
     *
     * @return RequestController
     */
    public function request(): RequestController
    {
        if (is_null($this->request)) {
            return new RequestController($this->wbs());
        }
        return $this->request;
    }

}