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

class CronManager_Block_Adminhtml_Form_Edit_Form_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('test_form', array('legend'=>'ref information'));
		$fieldset->addField('nom', 'text',
				array(
						'label' => '#',
						'class' => 'required-entry',
						'required' => true,
						'name' => 'nom',
				));
		$fieldset->addField('prenom', 'text',
				array(
						'label' => 'Plan CronTime',
						'class' => 'required-entry',
						'required' => true,
						'name' => 'prenom',
				));
		$fieldset->addField('telephone', 'text',
				array(
						'label' => 'Plan DateTime',
						'class' => 'required-entry',
						'required' => true,
						'name' => 'telephone',
				));
		if (Mage::registry('cron_manager_data')) {
			$form->setValues(Mage::registry('cron_manager_data')->getData());
		}
		return parent::_prepareForm();
	}
}