<?php

/**
 * Breadcrumb
 * 
 * Add contents and render breadcrumbs.
 * Crumbs are in FIFO (First In First Out)
 * 
 * @usage
 * $crumb = array(
 * 		'url' => '#',//The Url
 * 		'title' => 'This is the anchor title',//The Title
 * 		'anchor' => 'This is the link',//The Anchor to display
 * 		'attributes' => array|string,//Attributes to include
 * 	);
 * $this->dxBreadcrumb()->add($uniqueName, $crumb);
 * $this->dxBreadcrumbs()->render();
 */

namespace Dxapp\View\Helper;

use Dxapp\View\AbstractHelper;

class Breadcrumb extends AbstractHelper
{

	protected $breadcrumb = NULL;
	protected $crumbs = array();

	public function __invoke()
	{
		$this->add($this->getView()->dxConfig()->getOptions()->getBreadcrumbMain());
		return $this;
	}

	/**
	 * Set a whole breadcrumb.
	 * a list of breadcrumb without the ul tag
	 * e.g.
	 * <li><a href="/forsale" title="For Sale">For Sale</a> <span class="divider">/</span></li>
	 * <li><a href="/forsale" title="For Sale">For Sale</a> <span class="divider">/</span></li>
	 * <li><a href="/forsale" title="For Sale">For Sale</a> <span class="divider">/</span></li>
	 * @param string $breadCrumb 
	 */
	public function setBreadcrumb($breadcrumb)
	{
		$this->breadcrumb = $breadcrumb;
	}

	/**
	 * Add a crumb
	 * @param string $key unique key
	 * @param array $crumb assoc array with index title, anchor, url
	 * @param array $options 
	 * @TODO
	 */
	public function add($key, $crumb = array())
	{
		if (is_array($key))
		{
			foreach ($key as $k => $v)
			{
				$this->crumbs[$k] = $v;
			}
		}
		else
		{
			$this->crumbs[$key] = $crumb;
		}
		return $this;
	}

	/**
	 * Remove a crumb using the key
	 * @param string $key The index to remove
	 * @TODO
	 */
	public function remove($key)
	{
		if (isset($this->crumbs[$key]))
		{
			unset($this->crumbs[$key]);
		}
		return $this;
	}

	/**
	 * Clear the crumbs array
	 * @TODO
	 */
	public function clear()
	{
		$this->crumbs = array();
		return $this;
	}

	/**
	 * Render the Breadcrumb
	 * @return string 
	 */
	public function render()
	{
		if ($this->breadcrumb !== NULL)
		{
			$str = '';
			$str .= '<ul class="breadcrumb">';
			$str .= $this->breadcrumb;
			$str .= '</ul>';
			return $str;
		}
		if (!empty($this->crumbs) && is_array($this->crumbs))
		{
			$crumbs = '';
			$counter = 0;
			$total = count($this->crumbs);
			foreach ($this->crumbs as $val)
			{
				$active = FALSE;
				$divider = '<span class="divider">/</span>';
				$url = isset($val['url']) ? $val['url'] : '#';
				$title = isset($val['title']) ? $val['title'] : '';
				$anchor = isset($val['anchor']) ? $val['anchor'] : '<span style="color:red">ANCHOR IS MISSING</span>';
				if ($total == ($counter + 1))
				{
					$active = TRUE;
					$divider = '';
					$url = '#';
				}
				$crumbs .= '<li><a' . ($active ? ' class="active"' : '') . ' href="' . $url . '" title="' . $title . '">' . $anchor . '</a>' . $divider . '</li>';
				$counter++;
			}
			if (!empty($crumbs))
			{
				$str = '';
				$str .= '<ul class="breadcrumb">';
				$str .= $crumbs;
				$str .= '</ul>';
				$str .= '';
				return $str;
			}
		}
	}

}
