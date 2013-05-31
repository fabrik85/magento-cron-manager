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

class CronManager_Model_Observer
{
	protected $_gracePeriod = 3600;
	
    public function compare()
    {
    	$errors = array();
    	$collection = Mage::getModel('cron_manager/plan')->getPlanCollection();
    	foreach ($collection as $plan) {
    		$dates = Mage::getModel('cron_manager/plan')->cronToDate($plan->getScheduledAt());
    		$logs = Mage::getModel('cron_manager/log')->getLogCollection($plan->getId());
    		if (count($dates) !== count($logs)) {
	    		foreach ($dates as $key => $date) {
	    			$gracePeriod = strtotime($date) + $this->_gracePeriod;
	    			if (time() < $gracePeriod) {
	    				$logs[] = true;
	    			} 
	    			else {
	    				if (!key_exists($key, $logs)) {
	    					$errorTimes[] = $date;
	    				}
	    			}
	    		}
	    		if (count($dates) !== count($logs)) {
	    			foreach ($errorTimes as $errorTime) {
	    				$errorText = "We had trouble with plan: ".$plan->getName()." ## plan_id=".$plan->getId()." ## scheduled_at=".$errorTime;
	    				$errors[] = $errorText;
	    				Mage::log($errorText, null, 'cron_manager.log');
	    			}
	    		}
    		}
    	}
    	if ($errors) {
    		@mail('fabrik85@gmail.com','[cron_manager] Cron Job Error Report ',implode("\n", $errors));
    	}
    }
    /*
    public function refillScedule()
    {
    	Mage::getSingleton('core/resource')
    		->getConnection('core_write')
    		->query("TRUNCATE TABLE cron_manager_schedule");
    	$dates = Mage::getModel('cron_manager/plan')->cronToDate($expr);
    	for ($i=0; $i<count($dates); $i++) {
    		$planId[] = $planIds;
    		$name[] = $names;
    	}
    	
    	$sql = "INSERT INTO cron_manager_schedule 
    				(plan_id, name, time) 
    			VALUES (".implode(',', $planId).",
    					".implode(',', $name).",
    					".implode(',', $dates).")";
    	Mage::getSingleton('core/resource')->getConnection('core_write')->query($sql);
    }
    */
}
