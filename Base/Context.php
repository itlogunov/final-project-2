<?php

namespace Base;


class Context
{
    private static $_instance;

    /** @var Request */
    private $_request;

    /** @var Dispatcher */
    private $_dispatcher;

    /** @var DB */
    private $_db;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function instance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->_request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->_request = $request;
    }

    /**
     * @return Dispatcher
     */
    public function getDispatcher(): Dispatcher
    {
        return $this->_dispatcher;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher): void
    {
        $this->_dispatcher = $dispatcher;
    }

    /**
     * @return DB
     */
    public function getDb(): DB
    {
        return $this->_db;
    }

    /**
     * @param DB $db
     */
    public function setDb(DB $db): void
    {
        $this->_db = $db;
    }
}
