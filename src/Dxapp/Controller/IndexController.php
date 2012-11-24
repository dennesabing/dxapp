<?php
namespace Dxapp\Controller;

use Zend\Mvc\Controller\AbstractActionController as ActionController;
use Zend\View\Model\ViewModel;

class IndexController extends ActionController
{
	public function indexAction()
	{
		return new ViewModel();
	}
}
