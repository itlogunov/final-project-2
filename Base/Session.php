<?php


namespace Base;


class Session
{
    const FIELD_USER_ID = 'user_id';
    const FIELD_USER_IP = 'user_ip';
    const FIELD_USER_AGENT = 'user_agent';

    private static $_instance;

    private function __construct()
    {
        session_start();
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

    private function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    private function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function destroy()
    {
        session_destroy();
    }

    public function save(int $userId)
    {
        if ($userId <= 0) {
            throw new Exception('Невозможно сохранить сессию пользователя id=' . $userId);
        }

        $request = Context::instance()->getRequest();

        $this->set(self::FIELD_USER_ID, $userId);
        $this->set(self::FIELD_USER_IP, $request->getIp());
        $this->set(self::FIELD_USER_AGENT, $request->getUserAgent());
    }

    public function validate()
    {
        $request = Context::instance()->getRequest();

        if ($request->getIp() !== $this->get(self::FIELD_USER_IP)) {
            return false;
        }

        if (crc32($request->getUserAgent()) !== crc32($this->get(self::FIELD_USER_AGENT))) {
            return false;
        }

        return true;
    }

    public function getUserId()
    {
        return self::get(self::FIELD_USER_ID);
    }
}
