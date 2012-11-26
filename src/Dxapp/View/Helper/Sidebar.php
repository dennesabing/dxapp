<?php

/**
 * Sidebar
 * @package Dx\View\Helper
 * @author Dennes B Abing [dennes.b.abing@gmail.com]
 */

namespace Dxapp\View\Helper;

use Dxapp\View\AbstractHelper;

class Sidebar extends AbstractHelper
{
	/**
	 * The sidebar block partial
	 * @var string
	 */
	protected $partial = 'sidebar-block.phtml';
	
	/**
	 * The contents of the left bar
	 * @var array
	 */
	protected $left = array();
	
	/**
	 * The contents of the right bar
	 * @var array
	 */
	protected $right = array();
	
	public function __invoke()
	{
		return $this;
	}

	/**
	 * Append content to Left
	 * @param string $key a unique identifier
	 * @param array|string $content
	 * @param array $option
	 * @return \Dx\View\Helper\Sidebar 
	 */
	public function appendToLeft($key, $content, $option = array())
	{
		if(!empty($option))
		{
			$option['content'] = $content;
			$content = $option;
		}
		$this->add($key, $content, 'left');
		return $this;
	}
	
	/**
	 * Append content to Right
	 * @param string $key a unique identifier
	 * @param mixed array|string $content 
	 * @param array $option
	 * @return \Dx\View\Helper\Sidebar 
	 */
	public function appendToRight($key, $content, $option = array())
	{
		if(!empty($option))
		{
			$option['content'] = $content;
			$content = $option;
		}
		$this->add($key, $content, 'right');
		return $this;
	}
	
	/**
	 * Add to a sidebar
	 * @param string $key
	 * @param string $pane Sidebar position: left or right
	 * @param array|string $content
	 * array(
	 *  'content' => 'the content',
	 *  'before' => '$keyName', //place content before $keyName,
	 *  'after' => '$keyName', //place content after $keyName
	 *  'block' => 'TRUE|FALSE', //If to place content inside a block partial
	 *  'block' => array(
	 *    'enable' => TRUE|FALSE,
	 *    'title' => '',
	 *    'collapsible' => ''
	 *  ),
	 * )
	 * @return \Dx\View\Helper\Sidebar 
	 */
	public function add($key, $content, $pane)
	{
		if($pane == 'left')
		{
			$this->left[$key] = $content;
		} else {
			$this->right[$key] = $content;
		}
		return $this;
	}
	
	/**
	 * Remove a sidebar content
	 * @param string $key the unique identifier
	 * @param string $pane the pane where to remove the content
	 * 
	 * @return \Dx\View\Helper\Sidebar 
	 */
	public function remove($key, $pane = 'left')
	{
		if($pane == 'left')
		{
			$array = $this->left;
		} else {
			$array = $this->right;
		}
		if(isset($array[$key]))
		{
			unset($array[$key]);
		}
		return $this;
	}
	
	/**
	 * Render Right Sidebar
	 * @return string
	 */
	public function renderRight()
	{
		return $this->render($this->reposition($this->right));
	}
	
	/**
	 * Render Left Sidebar
	 * @return string
	 */
	public function renderLeft()
	{
		return $this->render($this->reposition($this->left));
	}
	
	/**
	 * Reposition Contents
	 * @param type $array
	 * @return array
	 */
	protected function reposition($array)
	{
		$newArray = $array;
		foreach($array as $key => $content)
		{
			if(is_array($content))
			{
				$pos = 'after';
				$reposition = FALSE;
				if(isset($content['before']) && !empty($content['before']))
				{
					$reposition = TRUE;
					$pos = 'before';
					$index = $content['before'];
				}
				if(isset($content['after']) && !empty($content['after']))
				{
					$reposition = TRUE;
					$pos = 'after';
					$index = $content['after'];
				}
				if($reposition && isset($newArray[$index]))
				{
					$contentx = $newArray[$key];
					unset($newArray[$key]);
					$newArray = \Dxapp\Utility\ArrayManager::array_insert($newArray, $index, array($key => $contentx), $pos);
				}
			}
		}
		return $newArray;
	}
	
	public function render($array)
	{
		$str = '';
		if(!empty($array))
		{
			foreach($array as $key => $val)
			{
				$content = is_array($val) ? $val['content'] : $val;
				$str .= $content;
			}
		}
		return $str;
	}
}
