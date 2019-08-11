<?php

namespace Base;


class Request
{
    private $_controllerName = '';
    private $_actionName = '';
    private $_params = [];

    public function __construct()
    {
        $parts = explode('/', $_SERVER['REQUEST_URI']);
        $this->_controllerName = $parts[1] ?? '';
        $this->_actionName = $parts[2] ?? '';

        $this->_paramsTypeGet();
    }

    private function _paramsTypeGet(): void
    {
        foreach ($_GET as $key => $value) {
            $this->_params[htmlspecialchars($key)] = htmlspecialchars($value);
        }
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
        return $this->_actionName;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->_params;
    }
}
