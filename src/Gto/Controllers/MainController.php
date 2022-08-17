<?php
namespace Gto\Controllers;

class MainController extends AbstractController
{
    public function main()
    {
        echo "Сработал метод: ";
        $this->view->renderHtml('main/main.php',[]);
    }

    public function test()
    {
        echo "Сработал метод test";
    }
}
