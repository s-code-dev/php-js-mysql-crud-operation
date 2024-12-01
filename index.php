<?php
/**
 * файл index.php
 * Здесь происходит вся логика взаимодействия routers с
 * controllers и вывод исключений с ошибками 400 500
 */
try {
    spl_autoload_register(function (string $className) {
        require_once __DIR__ . '/src/' . str_replace('\\', '/', $className) . '.php';
    });
    /**
     * @param  $route - все что передаем в строку брвузера
     * передается в этот route. Это происходит благодаря файлу .htaccess
     * .htaccess - файл настроен для cервера apache, чтобы были читаемые url
     */
    $route = $_GET['route'] ?? '';
    /**
     * @param  $routes - получам все данные о созданных routers
     */
    $routes = require __DIR__ . '/src/routes.php';
    /**
     * Циклом перебираем и сравниваем все созданные routes с тем,
     * который передали в страку браузера, если он существует, то запускаем нужный метод
     * контроллера
     */
    $isRouteFound = false;
    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches);
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }

    if (!$isRouteFound) {
        throw new \MyProject\Exceptions\NotFoundException();
    }

    unset($matches[0]);
    /**
     * $controllerName = $controllerAndAction[0]; - получаем имя класса контроллера
     */
    $controllerName = $controllerAndAction[0];
    /**
     * $actionName  = $controllerAndAction[1]; - получаем имя метода этого контроллера
     */
    $actionName = $controllerAndAction[1];
    /**
     * вызываем метод контроллера  и передаем данные в метод если они есть
     */
    $controller = new $controllerName();
    $controller->$actionName(...$matches);

} catch (\MyProject\Exceptions\DbException $e) {
    $view = new \MyProject\View\View(__DIR__ . '/templates/errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
} catch (\MyProject\Exceptions\NotFoundException $e) {
    $view = new \MyProject\View\View(__DIR__ . '/templates/errors');
    $view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
}
