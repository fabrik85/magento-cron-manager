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

class CronManager_Block_Adminhtml_Form extends Mage_Adminhtml_Block_Widget_Grid_Container //Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_controller = 'adminhtml_form';
		$this->_blockGroup = 'cron_manager';
		$this->setTemplate('cron_manager/items.phtml');
	}
	
	protected function _prepareLayout()
	{
		$this->setChild('run_calendar', 
			$this->getLayout()->createBlock(
				'Mage_Core_Block_Html_Calendar',
				'html_calendar',
				array('template' => 'page/js/calendar.phtml')
			)
		);
		$this->setChild('filter_button',
				$this->getLayout()->createBlock('adminhtml/widget_button')
				->setData(array(
						'label'     => Mage::helper('cron_manager')->__('Filter'),
						'onclick'   => 'cron_manager_filter.submit()',
						'class' => ''
				))
		);
		$this->setChild('grid', $this->getLayout()->createBlock('cron_manager/adminhtml_form_grid'));
		
		if (Mage::app()->getRequest()->getParam('selected_date')) die('Van!');
		return parent::_prepareLayout();
	}
	
	public function getRunCalendarHtml()
	{
		return $this->getChildHtml('run_calendar');
	}
	
	public function getFilterButtonHtml()
	{
		return $this->getChildHtml('filter_button');
	}
}
