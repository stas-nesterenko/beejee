<?php

namespace BJ\Controllers;

use BJ\AbstractController;

class NotFoundController extends AbstractController
{
    public function init()
    {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");

        return $this->view->render('pageNotFound');
    }
}
