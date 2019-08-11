<?php

namespace Base;


use Illuminate\Database\Capsule\Manager as Capsule;

class DbConnection
{
    /** @var Capsule */
    private $_capsule;

    public function openConnection()
    {
        if (!$this->_capsule) {
            $this->_capsule = new Capsule;

            $dbDriver = $_ENV['DB_DRIVER'] ?? 'mysql';
            $dbHost = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $dbPort = $_ENV['DB_PORT'] ?? 3306;
            $dbName = $_ENV['DB_NAME'] ?? '';
            $dbUser = $_ENV['DB_USER'] ?? 'root';
            $dbPassword = $_ENV['DB_PASSWORD'] ?? 'root';
            $dbCharset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';
            $dbCollation = $_ENV['DB_COLLATION'] ?? 'utf8mb4_unicode_ci';
            $dbPrefix = $_ENV['DB_PREFIX'] ?? '';

            $this->_capsule->addConnection([
                'driver' => $dbDriver,
                'host' => $dbHost,
                'port' => $dbPort,
                'database' => $dbName,
                'username' => $dbUser,
                'password' => $dbPassword,
                'charset' => $dbCharset,
                'collation' => $dbCollation,
                'prefix' => $dbPrefix,

            ]);
            $this->_capsule->setAsGlobal();
            $this->_capsule->bootEloquent();
        }

        return $this->_capsule;
    }
}
