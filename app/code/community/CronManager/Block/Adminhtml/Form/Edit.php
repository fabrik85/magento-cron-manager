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

class CronManager_Block_Adminhtml_Form_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_objectId = 'id';
		//we assign the same blockGroup as the Grid Container
		$this->_blockGroup = 'cron_manager';
		//and the same controller
		$this->_controller = 'adminhtml_form';
		//define the label for the save and delete button
		$this->_updateButton('save', 'label','save reference');
		$this->_updateButton('delete', 'label', 'delete reference');
	}
	
	//Change $this->_headerText in Storesco_cron_manager_Block_Adminhtml_Form class
	public function getHeaderText()
	{
		if( Mage::registry('cron_manager_data')&&Mage::registry('cron_manager_data')->getId()) {
			return 'Log content for [\'PLAN NAME\'] ';
			//$this->htmlEscape(Mage::registry('cron_manager_data')->getTitle()).'<br />';
		}
		else {
			return 'Cron Manager Interface';
		}
	}
}