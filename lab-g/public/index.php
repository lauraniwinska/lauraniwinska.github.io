<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';

$config = new \App\Service\Config();

$templating = new \App\Service\Templating();
$router = new \App\Service\Router();

$action = $_REQUEST['action'] ?? null;
$view = null;
switch ($action) {
    case 'post-index':
    case null:
        $controller = new \App\Controller\PostController();
        $view = $controller->indexAction($templating, $router);
        break;
    case 'post-create':
        $controller = new \App\Controller\PostController();
        $view = $controller->createAction($_REQUEST['post'] ?? null, $templating, $router);
        break;
    case 'post-edit':
        if (! isset($_REQUEST['id'])) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view = $controller->editAction($_REQUEST['id'], $_REQUEST['post'] ?? null, $templating, $router);
        break;
    case 'post-show':
        if (! isset($_REQUEST['id'])) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view = $controller->showAction($_REQUEST['id'], $templating, $router);
        break;
    case 'post-delete':
        if (! isset($_REQUEST['id'])) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view = $controller->deleteAction($_REQUEST['id'], $router);
        break;
    case 'music-index':
        $controller = new \App\Controller\MusicController();
        $view = $controller->indexAction($templating, $router);
        break;
    case 'music-create':
        $controller = new \App\Controller\MusicController();
        $view = $controller->createAction($_REQUEST['music'] ?? null, $templating, $router);
        break;
    case 'music-edit':
        if (! isset($_REQUEST['id'])) {
            break;
        }
        $controller = new \App\Controller\MusicController();
        $view = $controller->editAction($_REQUEST['id'], $_REQUEST['music'] ?? null, $templating, $router);
        break;
    case 'music-show':
        if (! isset($_REQUEST['id'])) {
            break;
        }
        $controller = new \App\Controller\MusicController();
        $view = $controller->showAction($_REQUEST['id'], $templating, $router);
        break;
    case 'music-delete':
        if (! isset($_REQUEST['id'])) {
            break;
        }
        $controller = new \App\Controller\MusicController();
        $view = $controller->deleteAction($_REQUEST['id'], $router);
        break;
    case 'info':
        $controller = new \App\Controller\InfoController();
        $view = $controller->infoAction();
        break;
    default:
        $view = 'Not found';
        break;

}

if ($view) {
    echo $view;
}
