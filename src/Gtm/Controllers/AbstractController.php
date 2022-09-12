<?php
namespace Gtm\Controllers;

use Gtm\Models\Users\UsersAuthService;
use Gtm\View\View;
use Gtm\Services\Db;

abstract class AbstractController
{
    /** @var View */
    protected $view;

    /** @var Db */
    protected $db;

    protected $user;

    public function __construct()
    {
        $this->view = new View(__DIR__. '/../../templates/');
        $this->db = new Db();
        $this->user = UsersAuthService::getUserByToken();
        $this->view->setVar('user', $this->user);
    }
}