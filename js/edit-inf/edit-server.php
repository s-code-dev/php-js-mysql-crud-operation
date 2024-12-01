<?php

spl_autoload_register(function($className){
    include __DIR__.'/../../src/'. str_replace('\\', '/', $className).'.php';
});

 use MyProject\Controllers\UsersController; 
 
$obj = new UsersController();
/**
 * Получам данные через ajax запрос и передаем их в
 * @method getEditForm контроллера  UsersController.
 * @param $form Возращаем форму user по id
 */
if(!empty($_SERVER['REQUEST_METHOD'] === 'POST')){

    $data = file_get_contents('php://input');

    $form = $obj->getEditForm((int) $data);

    echo $form;

}