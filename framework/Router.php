<?php

namespace Framework;

use Exceptions\NotFound;

class Router {

    /**
     * @var array
     */
    private $_routes;

    public function __construct() {
        $this->_routes = yaml_parse_file(__DIR__ . '/../routes.yml');
    }

    /**
     * @param string $requestUri
     * @throws NotFound
     * @return Route
     */
    public function match($requestUri) {
        $matches = explode('/', $requestUri);
        $isActionSpecified = count($matches) > 1 && end($matches) !== '';
        $action = $isActionSpecified ? array_pop($matches) . 'Action' : 'indexAction';
        $mainPath = implode($matches) ?: '/';

        if (!array_key_exists($mainPath, $this->_routes)) {
            throw new NotFound("Controller {$mainPath} was not found");
        }

        $controller = "Controllers\\" . $this->_routes[$mainPath]['controller'];

        if (!in_array($action, get_class_methods($controller))) {
            throw new NotFound("Action {$action} in controller {$controller} was not found");
        }

        $routeName = $this->_routes[$mainPath]['name'];

        return new Route($routeName, $controller, $action);
    }

    /**
     * @return array
     */
    public function getRoutes() {
        return $this->_routes;
    }

}