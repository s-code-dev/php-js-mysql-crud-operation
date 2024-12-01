<?php

namespace MyProject\Controllers;

use MyProject\Controllers\AbstractController;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Models\Users\User;
use MyProject\View\View;

/**
 * Summary of UsersController - контроллер взаимодействия с user
 */
class UsersController extends AbstractController
{

    /**
     * @method add() - добавления user
     * @return void
     */
    public function add(): void
    {
        if (!empty($_POST)) {
            $users = User::getAllUsers();
            try {
                if (User::addUser($_POST)) {

                    header('Location: /', true, 302);
                    exit;
                }

            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('main/main.php', ['error' => $e->getMessage(), 'users' => $users]);
                return;
            }
        }

    }
    /**
     * @method delete() - удаления user по id
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $user = User::getById($id);
        // var_dump($user);
        if ($user->delete()) {

            header('Location: /', true, 302);

        }

    }
    /**
     * @method getEditForm($id) - получения шаблона формы по id user
     * @param  int $id
     * @return mixed
     */
    public function getEditForm(int $id): mixed
    {

        $user = User::getInfUserForId($id);

        return $this->view->renderHTML('main/edit.php', ['user' => $user]);

    }

    /**
     * @method edit($id) - изименения данных по id user
     * @param  int $id
     * @return  void
     */

    public function edit(int $id): void
    {
        $user = User::getById($_POST['id']);
        $users = User::getAllUsers();

        if (!empty($_POST)) {

            try {
                $user->updateDataUsery($_POST);
                header('Location: /', true, 302);
                exit();

            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('main/main.php', ['error' => $e->getMessage(), 'users' => $users]);
                return;
            }

        } else {
            header('Location: /', true, 302);
            exit();
        }

    }

}
