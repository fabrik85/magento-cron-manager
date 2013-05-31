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
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Attila Fabrik <fabrik85@gmail.com>
 */

class CronManager_Model_Log extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('cron_manager/log');
    }
    
    public function getLogCollection($planId)
    {
    	$now = date('Y-m-d 00:00:00');
    	$fromDate = date('Y-m-d H:i:s', strtotime($now) - 24 * 60 * 60);
    	
    	return $this->getCollection()
    		->addFieldToFilter('plan_id',$planId)
    		->addFieldToFilter('created_at',"BETWEEN $fromDate AND $now");
    }
}