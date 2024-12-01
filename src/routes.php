<?php
/**
 * В этом файле созднаны routes для вызова нужный метод контроллера
 * при вводе созданного url
 */
return [
    '~^$~' => [\MyProject\Controllers\MainController::class, 'show'],
    '~^edit/(\d+)$~' => [\MyProject\Controllers\UsersController::class, 'edit'],
    '~^users/(\d+)/delete$~' => [\MyProject\Controllers\UsersController::class, 'delete'],
    '~^users/add$~' => [\MyProject\Controllers\UsersController::class, 'add'],

];
