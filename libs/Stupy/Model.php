<?php

namespace Libs\Stupy;

class Model extends \Phalcon\Mvc\Model {
	public function __get($propName) {
		return $this->getDI()->get($propName);
	}

	/**
	 * 兼容opencart db->query()
	 *
	 * @sql: sql语句
	 * @return：
	 *		读操作，返回数组: array([first row], [all rows], [num of rows])
	 *		写操作返回int: num of rows affected
	 */
	public function db_query($sql, $bindParams = null, $bindTypes = null) {
		if(strtoupper(substr(ltrim($sql), 0, 6)) == 'SELECT') {
			$dbAdapter = $this->getReadConnection();
			$stmt = $dbAdapter->query($sql, $bindParams, $bindTypes);
			if ($stmt) {
				$stmt->setFetchMode(\Phalcon\Db::FETCH_ASSOC);
				$results = new \stdClass();
				$results->rows = $stmt->fetchAll();
				$results->row = isset($results->rows[0]) ? $results->rows[0] : array();
				$results->num_rows = $stmt->numRows();
				return $results;
			} else {
				throw new \Exception('DB Error: ' . $sql);
			}

		} else {
			$dbAdapter = $this->getWriteConnection();
			$ret = $dbAdapter->execute($sql, $bindParams, $bindTypes);
			return $dbAdapter->affectedRows();
		}
	}

	/**
	 * 兼容opencart db->escape()
	 */
	public function db_escape($value) {
		// pdo::quote return string with quote arround
		return substr($this->getReadConnection()->escapeString($value), 1, -1);
	}

	/**
	 * 兼容opencart db->getLastId()
	 */
	public function db_getLastId() {
		return $this->getWriteConnection()->lastInsertId();
	}

	//public function fetchAll($sql) {
	//}
}

