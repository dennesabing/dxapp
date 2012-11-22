<?php

/**
 * Alert/Response
 * 
 * Responses and messages
 * 
 * @usage
 * 	$this->dxAlerts()->add('Error my friend', 'error');
 * 	$this->dxAlerts()->addError('Error my friend');
 * 	$this->dxAlerts()->addSuccess('Success my friend');
 * 	$this->dxAlerts()->addInfo('Info my friend');
 * 	$this->dxAlerts()->render();
 *  $this->dxAlerts()->clear();
 */

namespace Dxapp\View\Helper;

use Dxapp\View\AbstractHelper;

class Alert extends AbstractHelper
{

	/**
	 * Array of alerts
	 * @var array
	 */
	protected $alerts = array();
	
	/**
	 * Alerts to be positioned
	 * @var array
	 */
	protected $positionedAlerts = array();
	
	/**
	 * Alerts that is to be passed to the next request
	 * @var array
	 */
	protected $sessionedAlerts = array();

	public function __invoke()
	{
		return $this;
	}

	/**
	 * Add a msg to alerts
	 * @param string $msg The alert to append
	 * @param string $type The type of alert
	 * @param string $pos Where you want to appear the Alert.
	 * @param boolean $nextPage If to pass the alert to the next page
	 * @TODO $pos
	 * @return \Dx\View\Helper\Alert 
	 */
	public function add($msg, $type, $pos = NULL, $nextPage = FALSE)
	{
		if($pos != NULL)
		{
			$this->positionedAlerts[$pos][$type] = $msg;
		}
		if($nextPage)
		{
			$this->sessionedAlerts[$type] = $msg;
		}
		$this->alerts[$type][] = $msg;
		return $this;
	}

	/**
	 * Add an error alert
	 * @param string $msg
	 * @param string $pos Where you want to appear the Alert.
	 * @param boolean $nextPage If to pass the alert to the next page
	 * @TODO $pos
	 * @return \Dx\View\Helper\Alert 
	 */
	public function addError($msg, $pos = NULL, $nextPage = FALSE)
	{
		$this->add($msg, 'error', $pos, $nextPage);
		return $this;
	}

	/**
	 * Add a notice alert
	 * @param string $msg
	 * @param string $pos Where you want to appear the Alert.
	 * @param boolean $nextPage If to pass the alert to the next page
	 * @TODO $pos
	 * @return \Dx\View\Helper\Alert 
	 */
	public function addNotice($msg, $pos = NULL, $nextPage = FALSE)
	{
		$this->add($msg, 'notice', $pos, $nextPage);
		return $this;
	}

	/**
	 * Add a success alert
	 * @param string $msg
	 * @param string $pos Where you want to appear the Alert.
	 * @param boolean $nextPage If to pass the alert to the next page
	 * @TODO $pos
	 * @return \Dx\View\Helper\Alert 
	 */
	public function addSuccess($msg, $pos = NULL, $nextPage = FALSE)
	{
		$this->add($msg, 'success', $pos, $nextPage);
		return $this;
	}

	/**
	 * Add an info alert
	 * @param string $msg
	 * @param string $pos Where you want to appear the Alert.
	 * @param boolean $nextPage If to pass the alert to the next page
	 * @TODO $pos
	 * @return \Dx\View\Helper\Alert 
	 */
	public function addInfo($msg, $pos = NULL, $nextPage = FALSE)
	{
		$this->add($msg, 'info', $pos, $nextPage);
		return $this;
	}

	/**
	 * Render the alerts 
	 */
	public function render()
	{
		$str = '';
		if (!empty($this->alerts))
		{
			foreach ($this->alerts as $type => $msgs)
			{
				$str .= '<div class="alert alert-' . $type . '"><button class="close" data-dismiss="alert" type="button"> × </button>';
				$msgCounter = 0;
				foreach ($msgs as $msg)
				{
					$msgCounter++;
					$str .= $msg . ($msgCounter < count($msgs) ? '<br />' : '');
				}
				$str .= '</div>';
			}
		}
		return $str;
	}

	/**
	 * Clear the alerts
	 * @param string $type Type of Alert to clear
	 * 
	 * @return \Dx\View\Helper\Alert 
	 */
	public function clear($type = NULL)
	{
		if ($type == NULL)
		{
			$this->alerts = array();
		}
		else
		{
			if (isset($this->alerts[$type]))
			{
				$this->alerts[$type] = array();
			}
		}
		return $this;
	}
	
	/**
	 * Render
	 * @return type
	 */
	public function __toString()
	{
		return $this->render();
	}

}
