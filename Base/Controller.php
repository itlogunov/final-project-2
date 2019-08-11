<?php

namespace Base;


class Controller
{
    /** @var View */
    public $view;

    /** @var bool */
    protected $_render = true;

    /**
     * @return bool
     */
    public function isRender(): bool
    {
        return $this->_render;
    }
}
