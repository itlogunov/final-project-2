<?php

namespace Base;


class Request
{
    private $_controllerName = '';
    private $_actionName = '';
    private $_requestUri = '';
    private $_requestParams = [];

    public function __construct()
    {
        $this->_requestUri = trim($_SERVER['REQUEST_URI']);

        $parts = explode('/', $this->_requestUri);
        $this->_controllerName = $parts[1] ?? '';
        $this->_actionName = $parts[2] ?? '';

        $this->_setRequestParams();
    }

    private function _setRequestParams(): void
    {
        foreach ($_REQUEST as $key => $value) {
            $this->_requestParams[trim(htmlspecialchars($key))] = trim(htmlspecialchars($value));
        }
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'];
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
    public function getRequestParams(): array
    {
        return $this->_requestParams;
    }
}
