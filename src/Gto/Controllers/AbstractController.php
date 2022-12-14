<?php
namespace Gto\Controllers;

use Gto\View\View;

abstract class AbstractController
{
    /** @var View */
    protected $view;

    public function __construct()
    {
        $this->view = new View(__DIR__. '/../../templates/');
        // $this->view->setVar('user', $this->user);
    }
}