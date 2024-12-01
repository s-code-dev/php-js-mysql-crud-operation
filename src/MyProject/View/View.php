<?php

namespace MyProject\View;

/**
 * Класс View предназначен для работы с templates
 */

class View
{
    /**
     * @var string  $templatesPath - путь до шаблонов страниц
     */
    private $templatesPath;

    /**
     * Конструктор класса View
     * @param string $templatesPath - - путь до шаблонов страниц
     */

    public function __construct(string $templatesPath)
    {
        $this->templatesPath = $templatesPath;
    }

    /**
     * @method  renderHtml() - метод для отображения templates
     * @param string $templateName - имя шаблона страницы
     * @param array $vars - данные которые передаем в шаблон
     * @param int $code - response code 100 200 300 400 500
     */

    public function renderHtml(string $templateName, array $vars = [], int $code = 200)
    {
        http_response_code($code);
        extract($vars);

        ob_start();
        include $this->templatesPath . '/' . $templateName;
        $buffer = ob_get_contents();
        ob_end_clean();

        echo $buffer;

    }

}
