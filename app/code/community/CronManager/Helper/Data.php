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

class CronManager_Helper_Data extends Mage_Core_Helper_Abstract
{   
    /**
     * Log the plan.
     *
     * @param intager $planId
     * @param (optional) mixed $flag
     */
    public function log($planId, $flag = NULL)
    {
    	//TODO cron job start date!
    	if($name = $this->getNameById($planId)) {
    		Mage::getModel('cron_manager/log')
    		->setPlanId($planId)
    		->setCreatedAt(date('c'))
    		->setFlag($flag)
    		->save();
    	}
    }
    
    /**
     * Retrive plan name by id.
     * 
     * @param integer $planId
     * @return string
     */
    protected function getNameById($planId = NULL)
    {
        if(isset($planId) && is_numeric($planId)) {
            $plan = Mage::getModel('cron_manager/plan')->load($planId);
            return $plan->getName();
        }
    }
}
