<?php
namespace App\Controller;

use Framework\Mvc\HttpController;

class HomeController extends HttpController
{
    /**
     * Homepage
     */
    public function indexAction()
    {
        $this->viewModel->render('index.twig');
    }
}