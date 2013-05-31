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

class CronManager_Block_Adminhtml_Form_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('cron_managerGrid');
		$this->setDefaultSort('plan_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}
	
	/* protected function _prepareLayout()
	{
		$this->getLayout()->getBlock('content')->append(
				$this->getLayout()->createBlock(
						'Mage_Core_Block_Html_Calendar',
						'html_calendar',
						array('template' => 'page/js/calendar.phtml')
				)
		);
		return parent::_prepareLayout();
	} */
	
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('cron_manager/plan')->getPlanCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns()
	{
		$this->addColumn('plan_id', array(
				'header'    => Mage::helper('cron_manager')->__('Plan ID'),
				'align'     =>'left',
				'index'     => 'plan_id',
				'width'		=> '50px'
		));
		
		$this->addColumn('plan_name', array(
				'header'    => Mage::helper('cron_manager')->__('Name'),
				'align'     =>'left',
				'index'     => 'name'
		));
        
		$this->addColumn('flag', array(
				'header'    => Mage::helper('cron_manager')->__('Flag'),
				'align'     =>'left',
				'index'     => 'plan_id',
				'renderer'  => 'cron_manager/adminhtml_form_renderer_flag'
		));
		
		/* $this->addColumn('status', array(
				'header'    => Mage::helper('cron_manager')->__('Status'),
				'align'     =>'left',
				'index'     => 'status',
				'type'      => 'options',
				'options'   => array(
						1 => Mage::helper('cron_manager')->__('Live'),
						0 => Mage::helper('cron_manager')->__('Pending')
				),
		)); */
		//$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
		return parent::_prepareColumns();
	}
	
	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('log_id');
		$this->getMassactionBlock()->setFormFieldName('log');
	
		$this->getMassactionBlock()->addItem('run', array(
				'label'    => Mage::helper('cron_manager')->__('Run'),
				'url'      => $this->getUrl('*/*/run'),
				'confirm'  => Mage::helper('cron_manager')->__('Are you sure?')
		));
	
		return $this;
	}
	
	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}
