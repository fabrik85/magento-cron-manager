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

class CronManager_Block_Adminhtml_Form_Edit_Form_Tab extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('cron_manager_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle('Information sur le contact');
	}
	
	protected function _beforeToHtml()
	{
		$this->addTab('form_section', array(
				'label' => 'Contact Information',
				'title' => 'Contact Information',
				'content' => $this->getLayout()
				->createBlock('cron_manager/adminhtml_form_edit_tab_form')
				->toHtml()
		));
		return parent::_beforeToHtml();
	}
}