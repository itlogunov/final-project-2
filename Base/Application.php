<?php

namespace Base;


use App\Models\User;
use Base\Exceptions\Error404;

class Application
{
    /** @var Context */
    private $_context;

    private function _init()
    {
        $this->_context = Context::instance();

        $request = new Request();
        $dispatcher = new Dispatcher();
        $dbConnection = new DbConnection();
        $dbConnection->openConnection();

        $this->_context->setRequest($request);
        $this->_context->setDispatcher($dispatcher);
        $this->_context->setDbConnection($dbConnection);
    }

    private function _initUser()
    {
        $session = Session::instance();
        $userId = $session->getUserId();
        if ($userId) {
            if ($session->validate()) {
                $user = new User();
                $this->_context->setUser($user);
            }
        }
    }

    public function run()
    {
        try {

            self::_init();
            self::_initUser();

            $this->_context->getDispatcher()->dispatch();
            $dispatcher = $this->_context->getDispatcher();

            $controllerFileName = 'App\Controllers\\' . $dispatcher->getControllerName();
            if (!class_exists($controllerFileName)) {
                throw new Error404('Class ' . $controllerFileName . ' not exists');
            }

            /** @var Controller $controllerObject */
            $controllerObject = new $controllerFileName();

            $actionName = $dispatcher->getActionName();
            if (!method_exists($controllerObject, $actionName)) {
                throw new Error404('Method ' . $actionName . ' not found in ' . $controllerFileName);
            }

            $templatePath = '../App/Views/' . $dispatcher->getControllerName() . '/' .
                $dispatcher->getActionToken() . '.phtml';

            $view = new View();
            $controllerObject->view = $view;
            $controllerObject->$actionName();

            if ($controllerObject->isRender()) {
                echo $view->render($templatePath);
            }

        } catch (Error404 $e) {
            header('HTTP/1.0 404 Not Found');
            echo '<h1>ошибка 404</h1>';
            trigger_error($e->getMessage());
        }
    }
}