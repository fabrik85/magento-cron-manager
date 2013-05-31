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

class CronManager_Adminhtml_AdminformController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction() 
	{
		$this->loadLayout();
        //$this->_setActiveMenu('cron_manager');
        //$this->_addBreadcrumb(Mage::helper('cron_manager')->__('Form'), Mage::helper('cron_manager')->__('Form'));
		$this->renderLayout();
	}
	
	public function editAction()
	{
		$logId = $this->getRequest()->getParam('id');
		$logModel = Mage::getModel('cron_manager/log')->load($logId);
		if ($logModel->getId() || $logId == 0)
		{
			Mage::register('cron_manager_data', $logModel);
			$this->loadLayout();
			//$this->_setActiveMenu('test/set_time');
			//$this->_addBreadcrumb('test Manager', 'test Manager');
			//$this->_addBreadcrumb('Test Description', 'Test Description');
			//$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			$this->_addContent($this->getLayout()
					->createBlock('cron_manager/adminhtml_form_edit'));
					/*->_addLeft(
						$this->getLayout()
							->createBlock('cron_manager/adminhtml_form_edit_tabs')
					);*/
			$this->renderLayout();
		}
		else
		{
			Mage::getSingleton('adminhtml/session')
				->addError('Cron Manager does not exist');
			$this->_redirect('*/*/');
		}
	}
	
	public function massDeleteAction() 
	{
		$logIds = $this->getRequest()->getParam('log');
		if (!is_array($logIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cron_manager')->__('Please select log(s).'));
		}
		else {
			try {
				$logModel = Mage::getModel('cron_manager/log');
				foreach ($logIds as $logId) {
					$logModel->load($logId)->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(
						Mage::helper('cron_manager')->__('Total of %d record(s) were deleted.', count($logIds))
				);
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}
    
    /**
    * Check allow or not access to ths page
    *
    * @return bool - is allowed to access this menu
    */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cron_manager');
    }
}
