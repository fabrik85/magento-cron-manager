<?php
/**
 * CronManager
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @package    CronManager
 * @copyright  Copyright (c) 2013 Storesco Ltd.
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Attila Fabrik <fabrik85@gmail.com>
 */

class CronManager_Block_Adminhtml_Form_Renderer_Flag extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		//missing images
		$good = '<img src="'.$this->getSkinUrl('images/green_lamp.jpg').'">';
		$bad = '<img src="'.$this->getSkinUrl('images/red_lamp.jpg').'">';
		
		$value = $row->getData($this->getColumn()->getIndex());
        if (Mage::getModel('cron_manager/log')->getLogCollection($value)) {
        	return 0;//$bad;
        }
        else {
        	return 1;//$good;
        }
	}
}
