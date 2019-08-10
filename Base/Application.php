<?php

namespace Base;


use Base\Exceptions\Error404;

class Application
{
    /** @var Context */
    private $_context;

    private function _init()
    {
        $this->_context = Context::instance();

        $this->_context->setRequest(new Request());
        $this->_context->setDispatcher(new Dispatcher());
        $this->_context->setDb(new DB());
    }

    public function run()
    {
        try {

            self::_init();

            $this->_context->getDispatcher()->dispatch();
            $dispatcher = $this->_context->getDispatcher();

            $controllerFileName = 'App\Controllers\\' . $dispatcher->getControllerName();
            if (!class_exists($controllerFileName)) {
                throw new Error404('Class ' . $controllerFileName . ' not exists');
            }

            /** @var Controller */
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
            $view->render($templatePath);

        } catch (Error404 $e) {
            header('HTTP/1.0 404 Not Found');
            trigger_error($e->getMessage());
        }
    }
}