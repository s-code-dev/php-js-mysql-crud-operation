<?php
namespace MyProject\Controllers;

use MyProject\Controllers\AbstractController;
use MyProject\Models\Users\User;
use MyProject\View\View;

/**
 *  MainController - класс служит для отображения главной страницы
 */
class MainController extends AbstractController
{

    /**
     * @method  show()- метод служит для отображения главной страницы
     * @return  void
     */
    public function show(): void
    {

        $users = User::getAllUsers();
        $this->view->renderHtml('main/main.php', ['users' => $users]);
        return;

    }

}
