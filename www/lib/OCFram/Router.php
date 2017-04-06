<?php
namespace OCFram;
use RuntimeException;

/**
 *
 */
class Router
{

    const NO_ROUTE = 1;

    /**
     * Array containing routes models used to create routes.
     * @var array array of routes.
     */
    protected $routesModels = [];

    public function addRouteModel(Route $routeModel)
    {
        if (!in_array($routeModel, $this->routesModels)) {
            $this->routesModels[] = $routeModel;
        }
    }

    /**
     * Create a route using a URL and the routes models loaded in the router. If no route has been found, it throws a RuntimeException.
     * @param  String $url
     * @return Route
     * @throws RuntimeException
     */
    public function createRoute(String $url): Route
    {
        // Looking for a route model fitting the url
        foreach ($this->routesModels as $routeModel) {
            /**
             * @var Route $routeModel
             */
            if ($varsValues = $routeModel->match($url)) {
                array_shift($varsValues); // Remove the first value of the preg_match function
                // Checking if the route should contains variables. If so, hydrate them.
                $vars = [];
                if ($routeModel->hasVars()) {
                    $varsNames = $routeModel->getVarsNames();
                    $vars = array_combine($varsNames, $varsValues);
                }
                $routeModel->setVars($vars);
                return $routeModel;
            }
        }
        throw new RuntimeException("No route found for this URL", self::NO_ROUTE);
    }

}