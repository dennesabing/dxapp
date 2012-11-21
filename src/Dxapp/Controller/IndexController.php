<?php


namespace Dxapp\Controller;

use Dxapp\Controller\FrontendController;

class IndexController extends FrontendController
{
	public function indexAction()
	{
		return $this->getViewModel();
	}
}
