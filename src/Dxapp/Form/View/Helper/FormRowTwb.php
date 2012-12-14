<?php

namespace Dxapp\Form\View\Helper;

use DluTwBootstrap\Form\View\Helper\FormRowTwb as DluFormRowTwb;
use DluTwBootstrap\Form\View\Helper\FormElementTwb;
use DluTwBootstrap\Form\View\Helper\FormHintTwb;
use DluTwBootstrap\Form\View\Helper\FormDescriptionTwb;
use DluTwBootstrap\Form\View\Helper\FormElementErrorsTwb;
use DluTwBootstrap\Form\View\Helper\FormControlGroupTwb;
use DluTwBootstrap\Form\View\Helper\FormControlsTwb;
use DluTwBootstrap\Form\Exception\UnsupportedHelperTypeException;
use DluTwBootstrap\GenUtil;
use DluTwBootstrap\Form\FormUtil;
use Zend\Form\View\Helper\FormLabel;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * FormRowTwb
 * @package DluTwBootstrap
 * @copyright David Lukas (c) - http://www.zfdaily.com
 * @license http://www.zfdaily.com/code/license New BSD License
 * @link http://www.zfdaily.com
 * @link https://bitbucket.org/dlu/dlutwbootstrap
 */
class FormRowTwb extends DluFormRowTwb
{

	/**
	 * The element dx options
	 * @var array
	 */
	protected $dxOptions = NULL;

	/**
	 * FloatStart flag
	 * @var boolean
	 */
	protected $floatStart = FALSE;

	/**
	 * Return Dx Options
	 * @param type $element
	 * @return boolean
	 */
	public function getDxOptions($element)
	{
		$options = $element->getOptions();
		if (isset($options['dx']))
		{
			return $this->setDxOptions($options['dx']);
		}
		return array();
	}

	/**
	 * Set DX Options
	 * @param type $options
	 * @return \Dxapp\Form\View\Helper\FormRowTwb
	 */
	protected function setDxOptions($options)
	{
		$this->dxOptions = $options;
		return $this;
	}

	/**
	 * Check if element is to start the float
	 * @return boolean
	 */
	protected function floatStart()
	{
		if (isset($this->dxOptions['floatStart']) && $this->dxOptions['floatStart'])
		{
			$this->floatStart = TRUE;
			return $this->floatStart;
		}
		return FALSE;
	}

	/**
	 * Check if element is to end the float
	 * @return boolean
	 */
	protected function floatEnd()
	{
		if ($this->floatStart && isset($this->dxOptions['floatEnd']) && $this->dxOptions['floatEnd'])
		{
			$this->floatStart = FALSE;
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * REturn the controlGroupClass node
	 * @return string
	 */
	public function getControlGroupClass()
	{
		if (isset($this->dxOptions['controlGroupClass']))
		{
			return $this->dxOptions['controlGroupClass'] . ' ';
		}
		return '';
	}

	/**
	 * Utility form helper that renders a label (if it exists), an element, hint, description and errors
	 * @param ElementInterface $element
	 * @param string|null $formType
	 * @param array $displayOptions
	 * @param bool $renderErrors
	 * @return string
	 */
	public function render(ElementInterface $element, $formType = null, array $displayOptions = array(), $renderErrors = true)
	{
		$dxOptions = $this->getDxOptions($element);
		$formType = $this->formUtil->filterFormType($formType);
		$elementHelper = $this->getElementHelper();
		$elementErrorsHelper = $this->getElementErrorsHelper();
		$hintHelper = $this->getHintHelper();
		$descriptionHelper = $this->getDescriptionHelper();

		$label = (string) $element->getLabel();
		$elementString = $elementHelper->render($element, $formType, $displayOptions);

		//Hint, description and element errors are generated only for visible elements on horizontal and vertical forms
		//Divs for control-group and controls are generated only for visible elements on horizontal and vertical forms,
		//otherwise a blank vertical space is rendered
		if (($formType == FormUtil::FORM_TYPE_HORIZONTAL || $formType == FormUtil::FORM_TYPE_VERTICAL)
				&& !($element instanceof \Zend\Form\Element\Hidden)
				&& !($element instanceof \Zend\Form\Element\Csrf))
		{
			$controlGroupHelper = $this->getControlGroupHelper();
			$controlGroupOpen = $controlGroupHelper->openTag($element);
			$controlGroupClose = $controlGroupHelper->closeTag();
			$controlsHelper = $this->getControlsHelper();
			$controlsOpen = $controlsHelper->openTag($element);
			$controlsClose = $controlsHelper->closeTag();
			$hint = $hintHelper->render($element);
			$description = $descriptionHelper->render($element);
			if ($renderErrors)
			{
				$elementErrors = $elementErrorsHelper->render($element);
			}
			else
			{
				$elementErrors = '';
			}
		}
		else
		{
			$controlGroupOpen = '';
			$controlGroupClose = '';
			//We need some whitespace between label and element on inline and search forms
			$controlsOpen = "\n";
			$controlsClose = '';
			$hint = '';
			$description = '';
			$elementErrors = '';
		}

		if (!empty($label))
		{
			//Element has a label
			$labelHelper = $this->getLabelHelper();
			$label = $labelHelper($element, $displayOptions);
		}
		if ($this->floatStart())
		{
			$controlGroupOpen = '<div class="row">' . str_replace('class="', 'class="' . $this->getControlGroupClass(), $controlGroupOpen);
		}
		if ($this->floatEnd())
		{
			$controlGroupClose .= '</div>';
		}


		$markup = $controlGroupOpen
				. $label
				. $controlsOpen
				. $elementString
				. $hint
				. $description
				. $elementErrors
				. $controlsClose
				. $controlGroupClose;
		return $markup;
	}

}
