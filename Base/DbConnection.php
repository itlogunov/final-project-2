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
            $this->_capsule->addConnection([
                'driver' => 'mysql',
                'host' => '127.0.0.1',
                'database' => 'mvc',
                'username' => 'root',
                'password' => 'root',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'port' => 8889
            ]);
            $this->_capsule->setAsGlobal();
            $this->_capsule->bootEloquent();
        }

        return $this->_capsule;
    }
}
