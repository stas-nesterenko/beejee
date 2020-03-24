<?php

namespace BJ;

use Exception;
use Pixie\Connection;
use BJ\Controllers\NotFoundController;

/**
 * Управляющий синглтон
 *
 * Отвечает за первичную инициализацию системы.
 */
class Core
{
    /**
     * Содержит экземпляр объекта
     * @var
     */
    private static $_instance;

    /**
     * Core constructor.
     */
    private function __construct()
    {
    }

    /**
     *
     */
    private function __clone()
    {
    }

    /**
     * Возвращает экземпляр объекта
     * @return Core
     */
    public static function getInstance()
    {
        if (self::$_instance === null)
            self::$_instance = new self;

        return self::$_instance;
    }

    /**
     * Устанавливает временную зону, кодировку, запускает сессию.
     * @return bool
     */
    private function init()
    {
        date_default_timezone_set('Europe/Kiev');
        mb_internal_encoding('UTF-8');

        new Connection('mysql', [
            'driver'    => 'mysql',
            'host'      => SQL_HOST,
            'database'  => SQL_BASE,
            'username'  => SQL_LOGIN,
            'password'  => SQL_PWD,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'options'   => [
                \PDO::ATTR_TIMEOUT => 5,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ],
        ], 'DB');

        session_start();

        return true;
    }

    /**
     * Выполняет метод класа согласно совпадению шаблона роута с REQUEST_URI.
     * @return string html страницы
     * @throws Exception 404
     */
    public function run()
    {
        if (!$this->init())
            return 'Error in init();';

        try {
            $request_uri = strtok($_SERVER['REQUEST_URI'], '?');

            $routes = include './system/routes.php';

            if (isset($routes[$request_uri])) {
                list($controllerName, $controllerMethodName) = explode('@', $routes[$request_uri]);

                $controllerName = 'BJ\\Controllers\\' . $controllerName;
                $controller = new $controllerName();
                return $controller->{$controllerMethodName}();
            } else {
                throw new Exception('Страница не найдена', 404);
            }
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case 404:
                    $controller = new NotFoundController();
                    return $controller->init();
                    break;
                default:
                    die($e->getMessage());
                    break;
            }
        }
    }
}
