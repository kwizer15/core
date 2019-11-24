<?php

function ajaxHandle(AjaxController $controller)
{
    try {
        require_once __DIR__ . '/../../core/php/core.inc.php';
        include_file('core', 'authentification', 'php');

        if (!headers_sent()) {
            header('Content-Type: application/json');
        }

        $defaultAccess = $controller->getDefaultAccess();
        if (null !== $defaultAccess) {
            ajax::checkAccess('');
        }

        $action = init('action');
        if (!method_exists($controller, $action)) {
            throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . $action);
        }

        echo ajax::getResponse($controller->$action());
    } catch (Exception $e) {
        echo ajax::getResponse(displayException($e), $e->getCode());
    }
}

interface AjaxController
{
    /**
     * @return string|null
     */
    public function getDefaultAccess();
}
