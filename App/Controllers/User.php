<?php

namespace App\Controllers;


use Base\Context;
use Base\Controller;
use \App\Models\User as UserModel;

class User extends Controller
{
    public function indexAction()
    {
    }

    public function registerAction()
    {
    }

    public function listAction()
    {
        $users =  UserModel::with('images')->orderBy('age', 'desc')->limit(20)->get();
        $this->view->users = $users->toArray();
    }

    public function destroyAction()
    {
        $params = Context::instance()->getRequest()->getParams();
        if ((int)$params['id']) {
            UserModel::destroy((int)$params['id']);
            header('Location: /users');
            die();
        }
    }
}
