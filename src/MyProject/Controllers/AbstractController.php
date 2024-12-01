<?php

namespace MyProject\Controllers;

use MyProject\View\View;

/**
 * AbstractController - абстрактный класс для работы с рассположением templates
 */

abstract class AbstractController
{
    /**
     * @var $view -
     *
     * */

    protected $view;

    public function __construct()
    {

        $this->view = new View(__DIR__ . '/../../../templates');

    }
}
