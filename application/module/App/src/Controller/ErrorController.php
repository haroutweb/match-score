<?php
namespace App\Controller;

use Framework\Mvc\HttpController;

class ErrorController extends HttpController
{
    /**
     * not found page
     */
    public function error404Action()
    {
        header('HTTP/1.0 404 Not Found');
        $this->viewModel->render('404.twig');
    }
}