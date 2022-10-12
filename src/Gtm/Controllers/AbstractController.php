<?php
namespace Gtm\Controllers;

use Gtm\Models\Companys\Company;
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

    protected $company;

    public function __construct()
    {
        $this->view = new View(__DIR__. '/../../templates/');
        $this->db = new Db();
        $this->user = UsersAuthService::getUserByToken();
        $this->company = Company::getById(1);
        $this->view->setVar('user', $this->user);
        $this->view->setVar('company', $this->company);
    }
}