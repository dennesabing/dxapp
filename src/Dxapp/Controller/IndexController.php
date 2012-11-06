<?php
namespace Dxapp\Controller;

use Dx\Mvc\Controller\FrontendController;

class IndexController extends FrontendController
{
    public function indexAction()
    {
        return $this->getViewModel();
    }
}
