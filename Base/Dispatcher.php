<?php

namespace Base;


class Dispatcher
{
    const DEFAULT_CONTROLLER = 'User';
    const DEFAULT_ACTION = 'index';

    private $_controllerName = '';
    private $_actionToken = '';

    protected function getRoutes(): array
    {
        return [
            'Auth' => [
                'index' => 'User.index'
            ],
            'Register' => [
                'index' => 'User.register'
            ]
        ];
    }

    public function dispatch(): void
    {
        $request = Context::instance()->getRequest();

        $controllerName = $request->getControllerName();
        $actionName = $request->getActionName();

        if (!$controllerName || !$this->validate($controllerName)) {
            $this->_controllerName = self::DEFAULT_CONTROLLER;
        } else {
            $this->_controllerName = ucfirst(strtolower($controllerName));
        }

        if (!$actionName || !$this->validate($actionName)) {
            $this->_actionToken = self::DEFAULT_ACTION;
        } else {
            $this->_actionToken = strtolower($actionName);
        }

        $routes = $this->getRoutes();
        if (isset($routes[$this->_controllerName]) && isset($routes[$this->_controllerName][$this->_actionToken])) {
            list($this->_controllerName, $this->_actionToken) = explode('.', $routes[$this->_controllerName][$this->_actionToken]);
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function validate(string $key): bool
    {
        return preg_match('/[a-zA-Z0-9]+/', $key);
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->_controllerName;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->_actionToken . 'Action';
    }

    /**
     * @return string
     */
    public function getActionToken(): string
    {
        return $this->_actionToken;
    }
}
