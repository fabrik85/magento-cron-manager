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

$installer = $this;

$installer->startSetup();

$installer->run("CREATE TABLE IF NOT EXISTS `cron_manager_plan` (
  `plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `scheduled_at` varchar(255) NOT NULL,
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

$installer->run("CREATE TABLE IF NOT EXISTS `cron_manager_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `flag` varchar(255) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `FK_RUNKEEPER_PLAN_ID` (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$installer->run("ALTER TABLE `cron_manager_log`
  ADD CONSTRAINT `runkeeper_log_ibfk_1` FOREIGN KEY (`plan_id`) 
  REFERENCES `runkeeper_plan` (`plan_id`) 
  ON DELETE CASCADE ON UPDATE CASCADE;");

$installer->endSetup();
