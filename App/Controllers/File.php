<?php

namespace App\Controllers;


use App\Models\File as FileModel;
use Base\Context;
use Base\Controller;
use Base\Session;
use Intervention\Image\ImageManagerStatic as Image;

class File extends Controller
{
    private $_errors = [];

    public function listAction()
    {
        $userId = Session::instance()->getUserId();
        if (!$userId) {
            header('Location: /auth');
            die();
        }

        $files = FileModel::all();
        $this->view->files = $files->toArray();
    }

    public function saveUserPhotoFile($file)
    {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $photo = Image::make($file['tmp_name']);
        $photo->resize(200, null, function ($image) {
            $image->aspectRatio();
        });
        $photoName = '/images/' . md5(microtime()) . '.' . $ext;
        if (!$photo->save('../public' . $photoName)) {
            return false;
        }

        return $photoName;
    }

    public function saveUserPhotoToDb($photoName, $userId)
    {
        $this->deleteUserPhoto($userId);

        $file = new FileModel();
        $file->name = $photoName;
        $file->user_id = $userId;

        if (!$file->save()) {
            return false;
        }

        return true;
    }

    /**
     * Удаляем старую фотографию пользователя, если существует
     *
     * @param $userId
     */
    public function deleteUserPhoto($userId)
    {
        $oldFile = FileModel::where('user_id', '=', $userId)->first();
        if ($oldFile) {
            $this->deleteFileDisk($oldFile->name);
            FileModel::destroy($oldFile->getKey('id'));
        }
    }

    public function changeUserPhotoAction()
    {
        $this->_render = false;

        $file = $_FILES['photo'] ?? null;

        $validate = $this->validate($file);
        $requestParams = Context::instance()->getRequest()->getRequestParams();
        $userId = (int)$requestParams['user_id'];
        if ($userId <= 0) {
            $validate[] = 'Не передан идентификатор пользователя';
        }
        if (!empty($validate)) {
            array_map(function($error) {
                echo $error . '<br>';
            }, $validate);
            echo '<a href="/users">Вернуться назад</a>';
            die();
        }

        if ($photoName = $this->saveUserPhotoFile($file)) {

            // Сохраняем новый файл
            $this->saveUserPhotoToDb($photoName, $userId);
        }

        header('Location: /users');
        die();
    }

    public function deleteFileByUser(int $userId)
    {
        $file = FileModel::where('user_id', '=', $userId)->first();
        if ($file) {
            $this->deleteFileDisk($file->name);
            FileModel::destroy($file->id);
        }
    }

    public function deleteFileDisk($fileName)
    {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $fileName;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function destroyAction()
    {
        $requestParams = Context::instance()->getRequest()->getRequestParams();
        if ((int)$requestParams['id']) {

            $file = FileModel::find((int)$requestParams['id']);
            $this->deleteFileDisk($file->name);

            FileModel::destroy((int)$requestParams['id']);
            header('Location: /files');
            die();
        }
    }

    private function validate($file)
    {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $imgExt = ['jpeg', 'jpg', 'png'];

        if (!$file) {
            $this->_errors[] = 'Нет файла';
        } else {
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $this->_errors[] = 'Ошибка загрузки';
            }

            if (!in_array($ext, $imgExt)) {
                $this->_errors[] = 'Неверный формат изображения';
            }
        }

        return $this->_errors;
    }
}
