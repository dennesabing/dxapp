<?php
/**
 * BackendController
 *
 * Backend proxy to Dx ActionController
 *
 * @author Dennes <dennes.b.abing@gmail.com>
 */
namespace Dxapp\Controller;

use Dxapp\Controller\ActionController;

class BackendController extends ActionController
{
	protected $section = 'back';
}