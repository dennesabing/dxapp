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

	public function __invoke()
	{
		return $this;
	}

	/**
	 * Add a msg to alerts
	 * @param string $msg The alert to append
	 * @param string $type The type of alert
	 * @param string $pos Where you want to appear the Alert.
	 * @TODO $pos
	 * @return \Dx\View\Helper\Alert 
	 */
	public function add($msg, $type, $pos = NULL)
	{
		if($pos != NULL)
		{
			
		}
		$this->alerts[$type][] = $msg;
		return $this;
	}

	/**
	 * Add an error alert
	 * @param string $msg
	 * @param string $pos Where you want to appear the Alert.
	 * @TODO $pos
	 * @return \Dx\View\Helper\Alert 
	 */
	public function addError($msg, $pos = NULL)
	{
		$this->add($msg, 'error', $pos);
		return $this;
	}

	/**
	 * Add a success alert
	 * @param string $msg
	 * @param string $pos Where you want to appear the Alert.
	 * @TODO $pos
	 * @return \Dx\View\Helper\Alert 
	 */
	public function addSuccess($msg, $pos = NULL)
	{
		$this->add($msg, 'success', $pos);
		return $this;
	}

	/**
	 * Add an info alert
	 * @param string $msg
	 * @param string $pos Where you want to appear the Alert.
	 * @TODO $pos
	 * @return \Dx\View\Helper\Alert 
	 */
	public function addInfo($msg, $pos = NULL)
	{
		$this->add($msg, 'info', $pos);
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

}
