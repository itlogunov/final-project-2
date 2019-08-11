<?php

namespace Base;


class View
{
    protected $_data;

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    /**
     * @param $name
     * @return string
     */
    public function __get($name)
    {
        $value = '';
        if (isset($this->_data[$name])) {
            $value = $this->_data[$name];
        }

        return $value;
    }

    /**
     * @param string $template
     * @return bool
     */
    public function render(string $template)
    {
        ob_start();
        include '../App/Views/Layouts/header.phtml';
        include $template;
        include '../App/Views/Layouts/footer.phtml';
        return ob_get_clean();
    }
}
