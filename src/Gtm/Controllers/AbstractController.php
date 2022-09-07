<?php
namespace Gtm\Controllers;

use Gtm\View\View;
use Gtm\Services\Db;

abstract class AbstractController
{
    /** @var View */
    protected $view;

    /** @var Db */
    protected $db;

    public function __construct()
    {
        $this->view = new View(__DIR__. '/../../templates/');
        $this->db = new Db();
    }
}