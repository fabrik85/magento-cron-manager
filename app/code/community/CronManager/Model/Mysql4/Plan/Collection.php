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

class CronManager_Model_Mysql4_Plan_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	protected function _construct()
	{
		$this->_init('cron_manager/plan');
	}
	
	public function addFieldToFilter($field, $condition=null)
	{
		$field = $this->_getMappedField($field);
		$this->_select->where($this->_getConditionSql($field, $condition), null, Varien_Db_Select::TYPE_CONDITION);
		return $this;
	}
	
	public function _getConditionSql($fieldName, $condition) {
		if (is_array($fieldName)) {
			$orSql = array();
			foreach ($fieldName as $key=>$name) {
				if (isset($condition[$key])) {
					$orSql[] = '('.$this->_getConditionSql($name, $condition[$key]).')';
				} else {
					//if nothing passed as condition adding empty condition to avoid sql error
					$orSql[] = $this->getConnection()->quoteInto("$name = ?", '');
				}
			}
			$sql = '('. join(' or ', $orSql) .')';
			return $sql;
		}
	
		$sql = '';
		//$fieldName = $this->_getConditionFieldName($fieldName);
		if (is_array($condition) && isset($condition['field_expr'])) {
			$fieldName = str_replace(
					'#?',
					$this->getConnection()->quoteIdentifier($fieldName),
					$condition['field_expr']
			);
		}
		if (is_array($condition)) {
			if (isset($condition['from']) || isset($condition['to'])) {
				if (isset($condition['from'])) {
					if (empty($condition['date'])) {
						if ( empty($condition['datetime'])) {
							$from = $condition['from'];
						}
						else {
							$from = $this->getConnection()->convertDateTime($condition['from']);
						}
					}
					else {
						$from = $this->getConnection()->convertDate($condition['from']);
					}
					$sql.= $this->getConnection()->quoteInto("$fieldName >= ?", $from);
				}
				if (isset($condition['to'])) {
					$sql.= empty($sql) ? '' : ' and ';
	
					if (empty($condition['date'])) {
						if ( empty($condition['datetime'])) {
							$to = $condition['to'];
						}
						else {
							$to = $this->getConnection()->convertDateTime($condition['to']);
						}
					}
					else {
						$to = $this->getConnection()->convertDate($condition['to']);
					}
	
					$sql.= $this->getConnection()->quoteInto("$fieldName <= ?", $to);
				}
			}
			elseif (isset($condition['eq'])) {
				$sql = $this->getConnection()->quoteInto("$fieldName = ?", $condition['eq']);
			}
			elseif (isset($condition['neq'])) {
				$sql = $this->getConnection()->quoteInto("$fieldName != ?", $condition['neq']);
			}
			elseif (isset($condition['like'])) {
				$sql = $this->getConnection()->quoteInto("$fieldName like ?", $condition['like']);
			}
			elseif (isset($condition['nlike'])) {
				$sql = $this->getConnection()->quoteInto("$fieldName not like ?", $condition['nlike']);
			}
			elseif (isset($condition['rlike'])) {
				$sql = $this->getConnection()->quoteInto("$fieldName rlike ?", $condition['rlike']);
			}
			elseif (isset($condition['in'])) {
				$sql = $this->getConnection()->quoteInto("$fieldName in (?)", $condition['in']);
			}
			elseif (isset($condition['nin'])) {
				$sql = $this->getConnection()->quoteInto("$fieldName not in (?)", $condition['nin']);
			}
			elseif (isset($condition['is'])) {
				$sql = $this->getConnection()->quoteInto("$fieldName is ?", $condition['is']);
			}
			elseif (isset($condition['notnull'])) {
				$sql = "$fieldName is NOT NULL";
			}
			elseif (isset($condition['null'])) {
				$sql = "$fieldName is NULL";
			}
			elseif (isset($condition['moreq'])) {
				$sql = $this->getConnection()->quoteInto("$fieldName >= ?", $condition['moreq']);
			}
			elseif (isset($condition['gt'])) {
				$sql = $this->getConnection()->quoteInto("$fieldName > ?", $condition['gt']);
			}
			elseif (isset($condition['lt'])) {
				$sql = $this->getConnection()->quoteInto("$fieldName < ?", $condition['lt']);
			}
			elseif (isset($condition['gteq'])) {
				$sql = $this->getConnection()->quoteInto("$fieldName >= ?", $condition['gteq']);
			}
			elseif (isset($condition['lteq'])) {
				$sql = $this->getConnection()->quoteInto("$fieldName <= ?", $condition['lteq']);
			}
			elseif (isset($condition['finset'])) {
				$sql = $this->getConnection()->quoteInto("find_in_set(?,$fieldName)", $condition['finset']);
			}
			else {
				$orSql = array();
				foreach ($condition as $orCondition) {
					$orSql[] = "(".$this->_getConditionSql($fieldName, $orCondition).")";
				}
				$sql = "(".join(" or ", $orSql).")";
			}
		} else {
			$sql = $this->getConnection()->quoteInto("$fieldName = ?", (string)$condition);
		}
		return $sql;
	}
}