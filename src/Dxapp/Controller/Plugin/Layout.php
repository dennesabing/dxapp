<?php

namespace Dxapp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\Layout as ControllerLayout;
use Zend\EventManager\SharedEventManagerInterface as SharedEvents;
use Zend\Mvc\Exception;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\DispatchableInterface as Dispatchable;

class Layout extends ControllerLayout
{
	
	/**
	 * The serviceLocator
	 * @var object
	 */
	protected $locator = NULL;
	
    /**
     * Invoke as a functor
     *
     * If no arguments are given, grabs the "root" or "layout" view model.
     * Otherwise, attempts to set the template for that view model.
     *
     * @param  null|string $template
     * @return Model|Layout
     */
    public function __invoke($template = null)
    {
        if (null === $template) {
            return $this->getViewModel();
        }
        return $this->setTemplate($this->getLayout($template));
    }
	
	/**
	 * Fix/Set the layout based on the current application section
	 * @param string $layout
	 * @return string 
	 */
	public function getLayout($template)
	{
		$section = $this->getLocator()->get('dxapp_module_options')->getApplicationSection();
		if ($section == 'admin')
		{
			if (FALSE === strpos($template, 'admin-'))
			{
				$template = str_replace('/', '/admin-', $template);
			}
		}
		return $template;
	}
	
    /**
     * Get the locator
     *
     * @return ServiceLocatorInterface
     * @throws Exception\DomainException if unable to find locator
     */
    protected function getLocator()
    {
        if ($this->locator) {
            return $this->locator;
        }

        $controller = $this->getController();

        if (!$controller instanceof ServiceLocatorAwareInterface) {
            throw new Exception\DomainException('Forward plugin requires controller implements ServiceLocatorAwareInterface');
        }
        $locator = $controller->getServiceLocator();
        if (!$locator instanceof ServiceLocatorInterface) {
            throw new Exception\DomainException('Forward plugin requires controller composes Locator');
        }
        return $locator;
    }
}
