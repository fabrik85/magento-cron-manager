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

class CronManager_Model_Plan extends Mage_Core_Model_Abstract
{
	protected function _construct()
	{
		$this->_init('cron_manager/plan');
	}
	
	// in string like: $string = '\\'; return only 1 backslash, if you want 2 backslash write '\\\\'
	public function getPlanCollection()
	{
		//plain regexp for linux: 
		$yesterday = strtotime(date('c')) - 24 * 60 * 60;
		//min (0 - 59),
		$regexp = '^([0-9]+[[.space.]]|^\\*[[.space.]]|^\\*/[[:digit:]]{1,2}[[.space.]]|^([0-9]{1,2},)+[0-9]{1,2}[[.space.]])';
		//hour (0 - 23)
		$regexp .= '([0-9]+[[.space.]]|\\*[[.space.]]|\\*/[[:digit:]]{1,2}[[.space.]]|([0-9]{1,2},)+[0-9]{1,2}[[.space.]])';
		//day (1 - 31)
		$regexp .= '('.date('j', $yesterday).'[[.space.]]|\\*[[.space.]]'.$this->getBulletRegexp(date('j', $yesterday), date('t', $yesterday)).')';
		//month (1 - 12)
		$regexp .= '('.date('n', $yesterday).'[[.space.]]|\\*[[.space.]]'.$this->getBulletRegexp(date('n', $yesterday), 12).')';
		//day of week (0-6) or names
		$regexp .= '(\\*|'.date('l', $yesterday).'|'.date('w', $yesterday);
		$regexp .= '|[0-6]-[0-6]|[a-zA-Z]{3,9}-[a-zA-Z]{3,9})';
		
		$collection = $this->getCollection()
			->addFieldToFilter('scheduled_at', array('rlike' => $regexp));
		
		//get rid of wrong items if the week not equals!
		$schedule = Mage::getModel('cron/schedule');
		foreach ($collection->getItems() as $key => $item) {
			$cron = $schedule->setCronExpr($item->getScheduledAt())->getCronExprArr();
			if (strpos($cron[4],'-')!==false) {
				$week = explode('-', $cron[4]);
				if (date('w', $yesterday) < $schedule->getNumeric($week[0]) 
				 && date('w', $yesterday) > $schedule->getNumeric($week[1])) {
					$collection->removeItemByKey($key);
				}
			}
		}
		
		return $collection;
	}
	
	private function getBulletRegexp($search, $limit)
	{
		$regexp = '';
		$copy = $search;
		while ($search <= $limit) {
			$regexp .= '|\\*/'.$search.'[[.space.]]';
			$search += $search;
		}
		//before was: $regexp .= '|'.$copy.',)+[0-9]{1,2}[[.space.]]|([0-9]{1,2},)+'.$copy.'[[.space.]]';
		$regexp .= '|('.$copy.',)+[0-9]{1,2}[[.space.]]|([0-9]{1,2},)+'.$copy.'[[.space.]]';
		return $regexp;
	}
	
	public function cronToDate($crontab)
	{
		$schedule = Mage::getModel('cron/schedule');
		$cron = $schedule->setCronExpr($crontab)->getCronExprArr();
		foreach ($cron as $key => $expr) {
			if ($expr==='*') {
				$number = 0;
			}
			//handle range like: Monday-Friday
			elseif (strpos($expr,'-')!==false) {
				$e = explode('-', $expr);
				if (sizeof($e)!==2) {
					throw Mage::exception('Mage_Cron', "Invalid cron expression, expecting 'from-to' structure: ".$expr);
				}
				$from = $schedule->getNumeric($e[0]);
				$to = $schedule->getNumeric($e[1]);
				$number = array('from' => $from, 'to' => $to);
			}
			//handle listing like: 1,2,3
			elseif (strpos($expr,',')!==false) {
				$number = explode(',', $expr);
			}
			//handle listing like: */5
			elseif (strpos($expr,'/')!==false) {
				if ($key==4) {
					throw Mage::exception('Mage_Cron', "Invalid cron expression, expecting 'from-to' structure: ".$expr);
				}
				$e = explode('/', $expr);
				$number = $this->convertToNumbers($e[1], $key);
				if (!$number) {
					throw Mage::exception('Mage_Cron', "Invalid cron expression, expecting 'from-to' structure: ".$expr);
				}
			}
			//handle regular token like: 4
			else {
				$number = $schedule->getNumeric($expr);
			}
			$date[$key] = $number;
		}
		
		//Create dates like: 2012-12-31 10:20:00
		$dateformat = array();
		$yesterday = strtotime(date('c')) - 24 * 60 * 60;
		if ($date[3] == 0) $date[3] = date('m', $yesterday);
		if ($date[2] == 0) $date[2] = date('d', $yesterday);
		
		if ($cron[1] === '*' && $cron[0] !== '*') {
			for ($i=0;$i<24;$i++) {
				$dateformat = array_merge($dateformat, $this->convertToDates($date, $i));
			}
		}
		elseif ($cron[1] !== '*' && $cron[0] === '*') {
			for ($i=0;$i<60;$i++) {
				$dateformat = array_merge($dateformat, $this->convertToDates($date, false, $i));
			}
		}
		else {
			if ($cron[0] === '*' && $cron[1] === '*') {
				$limit = array(60,24);
				foreach ($limit as $key => $value) {
					$date[$key] = array();
					for ($c=1;$c<$value;$c++) {
						$date[$key][$c-1] = $c;
					}
				}
			}
			$dateformat = $this->convertToDates($date);
		}
		
		//handle range like: FRIDAY, Mon-Fri, etc...
		if ($cron[4] !== '*') {
			if (!is_array($date[4])) $date[4] = array($date[4]);
			$results = array();
			foreach ($dateformat as $value) {
				$dayOfMonth = date('w', strtotime($value));
				if (key_exists('to', $date[4]) && $dayOfMonth <= $date[4]['to'] && $dayOfMonth >= $date[4]['from']) {
					$results[] = $value;
				} 
				elseif ($dayOfMonth == $date[4][0]) {
					$results[] = $value;
				}
			}
			return $results;
		}
		return $dateformat;
	}
	
	private function convertToNumbers($expr_number, $format)
	{
		switch ($format) {
			case 0: $limit = 60; break;
			case 1: $limit = 24; break;
			case 2: $limit = date('t', strtotime(date('c')) - 24 * 60 * 60); break;
			case 3: $limit = 12; break;
		}
		
		$numbers = array();
		for ($i=$expr_number; $i<$limit; $i+=$expr_number) {
			$numbers[] = $i;
		}
		
		return $numbers;
	}
	
	public function convertToDates($date, $hour = NULL, $min = NULL)
	{
		$temp = array();
		$yesterday = strtotime(date('c')) - 24 * 60 * 60;
		$temp[] = date('Y', $yesterday).'-';
	
		for ($j=3; $j>=0; $j--)
		{
			if ($hour && $j==1) $date[$j] = $hour;
			if ($min && $j==0) $date[$j] = $min;
			switch ($j) {
				case 3: $separator = '-'; break;
				case 2: $separator = ' '; break;
				case 1: $separator = ':'; break;
				case 0: $separator = ':00'; break;
			}
									
			$add = array();
			foreach ($temp as $key => $value) {
				$template = $temp[$key];
				if (is_array($date[$j])) {
					foreach ($date[$j] as $subkey => $value) {
						if ($subkey == 0) {
							$temp[$key] .= sprintf('%02d', $value).$separator;
						}
						else {
							$add[] = $template.sprintf('%02d', $value).$separator;
						}
					}
				}
				else {
					$temp[$key] .= sprintf('%02d', $date[$j]).$separator;
				}
			}
			if ($add) $temp = array_merge($temp, $add);
		}
	
		return $temp;
	}
}
