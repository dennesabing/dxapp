<?php
/**
 * Config
 * 
 * Get the config
 *  
 */
namespace Dxapp\View\Helper;

use Dx\View\AbstractHelper;

class EscapeSlash extends AbstractHelper
{
    public function __invoke($str)
    {
        return $str;
    }
}
