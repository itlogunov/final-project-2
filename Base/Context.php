<?php

namespace Base;


use App\Models\User;

class Context
{
    private static $_instance;

    /** @var Request */
    private $_request;

    /** @var Dispatcher */
    private $_dispatcher;

    /** @var DbConnection */
    private $_dbConnection;

    /** @var User */
    private $_user;

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
     * @return DbConnection
     */
    public function getDbConnection(): DbConnection
    {
        return $this->_dbConnection;
    }

    /**
     * @param DbConnection $dbConnection
     */
    public function setDbConnection(DbConnection $dbConnection): void
    {
        $this->_dbConnection = $dbConnection;
    }

    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->_user = $user;
    }
}
