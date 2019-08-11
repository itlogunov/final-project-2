<?php

namespace App\Controllers;


use Base\Session;
use GUMP;
use Base\Context;
use Base\Controller;
use \App\Models\User as UserModel;

class User extends Controller
{
    public function indexAction()
    {
        $user = Context::instance()->getUser();
        if (!$user) {
            $request = Context::instance()->getRequest();
            $params = $request->getRequestParams();
            if (isset($params['auth']) && $params['auth'] == 1) {
                $validated = GUMP::is_valid($params, array(
                    'login' => 'required|valid_email',
                    'password' => 'required|max_len,100|min_len,6'
                ));

                if ($validated !== true) {
                    $this->view->errors = $validated;
                } else {
                    $user = UserModel::where('email', '=', $params['login'])->first();
                    if ($user) {
                        $hashPassword = $user->password;
                        $requestPassword = sha1($params['password']);
                        if ($hashPassword === $requestPassword) {
                            Session::instance()->save($user['id']);
                            $this->view->user = $user;
                        } else {
                            $this->view->errors = ['Пароль неверный'];
                        }
                    } else {
                        $this->view->errors = ['Логин не найден'];
                    }
                }

                $this->view->login = $params['login'] ?? '';
            }
        } else {
            $this->view->user = $user;
        }
    }

    public function registerAction()
    {
        $userId = Session::instance()->getUserId();
        if ($userId) {
            header('Location: /auth');
            die();
        }

        $request = Context::instance()->getRequest();
        $params = $request->getRequestParams();
        if (isset($params['register']) && $params['register'] == 1) {
            $validated = GUMP::is_valid(array_merge($params, $_FILES), array(
                'login' => 'required|valid_email',
                'name' => 'required|max_len,100|min_len,3',
                'age' => 'required|numeric|min_numeric,6|max_numeric, 100',
                'description' => 'required|max_len,200|min_len,3',
                'photo' => 'required_file|extension,png;jpg;jpeg',
                'password' => 'required|max_len,100|min_len,6',
                'password_confirm' => 'equalsfield,password'
            ));

            if ($validated !== true) {
                $this->view->errors = $validated;
            } else {
                $user = UserModel::where('email', '=', $params['login'])->first();
                if (!$user) {
                    $user = new UserModel();
                    $user->email = $params['login'];
                    $user->name = $params['name'];
                    $user->age = $params['age'];
                    $user->description = $params['description'];
                    $user->password = sha1($params['password']);
                    if ($user->save()) {

                        $userId = $user->getKey('id');

                        // Сохраняем фотографию
                        $file = new File();
                        $fileName = $file->saveUserPhotoFile($_FILES['photo']);
                        $file->saveUserPhotoToDb($fileName, $userId);

                        Session::instance()->save($userId);
                        header('Location: /auth');
                        die();

                    }
                } else {
                    $this->view->errors = ['Пользователь существует'];
                }
            }

            $this->view->fields = [$params];
        }
    }

    public function listAction()
    {
        $userId = Session::instance()->getUserId();
        if (!$userId) {
            header('Location: /auth');
            die();
        }

        $users = UserModel::with('images')
            ->orderBy('age', 'desc')
            ->limit(30)
            ->get();

        $users->each(function ($user) {
            if ($user->age >= 18) {
                $user->adult_status = 'Совершеннолетний';
            } else {
                $user->adult_status = 'Несовершеннолетний';
            }
        });

        $this->view->users = $users->toArray();
    }

    public function logoutAction()
    {
        Session::instance()->destroy();
        header('Location: /auth');
        die();
    }

    public function destroyAction()
    {
        $userId = Session::instance()->getUserId();
        if (!$userId) {
            header('Location: /auth');
            die();
        }

        $requestParams = Context::instance()->getRequest()->getRequestParams();
        if ((int)$requestParams['id']) {
            $file = new File();
            $file->deleteFileByUser((int)$requestParams['id']);
            UserModel::destroy((int)$requestParams['id']);

            if ((int)$requestParams['id'] === $userId) {
                Session::instance()->destroy();
            }

            header('Location: /users');
            die();
        }
    }
}
