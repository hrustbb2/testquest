<?php

namespace app;

use Aura\Router\Route;
use Aura\Router\RouterContainer;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;
use Pixie\Connection;
use Zend\Session\SessionManager;
use Zend\Session\Container;

/**
 * Приложение
 */
class App
{
    /**
     * @var App|null
     */
    private static $instance = null;

    /**
     * @var Array
     */
    private $config;

    /**
     * @var Container
     */
    private $db = null;

    /**
     * @var ServerRequest
     */
    private $request;

    /**
     * @var RouterContainer
     */
    private $routerContainer;

    /**
     * @var Container
     */
    private $sessionContainer = null;

    private function __construct()
    {
    }

    /**
     * Инстанс приложения
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Грузим конфиг
     *
     * @param $conf Array Массив параметров конфигурации
     */
    public function loadConf($conf)
    {
        $this->config = $conf;
    }

    /**
     * Возвращает конфиг
     *
     * @return Array
     */
    public function getConf()
    {
        return $this->config;
    }

    /**
     * Соединение с базой по требованию
     *
     * @return Connection
     */
    public function getDb()
    {
        if ($this->db === null) {
            $this->db = new Connection($this->config['db']['driver'], $this->config['db']);
        }
        return $this->db;
    }

    /**
     * Объект запроса
     *
     * @return ServerRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Роутер-контейнер для генерации урлов
     *
     * @return RouterContainer
     */
    public function getRouterContainer()
    {
        return $this->routerContainer;
    }

    /**
     * Инициализация сессий
     */
    public function sessionContainerInit()
    {
        if ($this->sessionContainer === null) {
            $sessionManager = new SessionManager();
            $this->sessionContainer = new Container($this->config['session']['sessionNameSpace'], $sessionManager);
        }
    }

    /**
     * Контейнер сессий
     *
     * @return Container
     */
    public function getSessionContainer()
    {
        return $this->sessionContainer;
    }

    /**
     * Вызов контроллера с указанными параметрами
     *
     * @param $routeConf Array
     * @param $route Route
     * @return Response
     */
    private function execController($routeConf, $route)
    {
        $controller = $routeConf['controller'];
        $method = $routeConf['action'];
        $obj = new $controller;
        $reflection = new \ReflectionMethod($obj, $method);
        $pass = array();
        foreach ($reflection->getParameters() as $param) {
            $pass[] = $route->attributes[$param->getName()] ?? $param->getDefaultValue();
        }
        $result = $reflection->invokeArgs($obj, $pass);
        if ($result instanceof Response) {
            $response = $result;
        } else {
            $response = (isset($routeConf['responseClass'])) ? new $routeConf['responseClass']($result) : new HtmlResponse($result);
        }
        $emiter = new SapiEmitter();
        $emiter->emit($response);
    }

    /**
     * Точка входа.
     */
    public function route()
    {
        $this->routerContainer = new RouterContainer();
        $map = $this->routerContainer->getMap();
        $routesConf = $this->config['routes'];
        foreach ($routesConf as $routeName=>$routeConf) {
            $r = $map->{$routeConf['method']}($routeName, $routeConf['url'], $routesConf[$routeName]);
            if (isset($routeConf['allows'])) {
                $r->allows($routeConf['allows']);
            }
        }
        $this->request = ServerRequestFactory::fromGlobals();
        $matcher = $this->routerContainer->getMatcher();
        $route = $matcher->match($this->request);
        if ($route) {
            $this->execController($route->handler, $route);
        } else {
            echo '404';
        }
    }
}
