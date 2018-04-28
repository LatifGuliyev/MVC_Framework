<?php

class DB {

	private $host, $dbname, $username, $pass, $db;

	function __construct($host = DB_HOST, $dbname = DB_NAME, $username = DB_USERNAME, $pass = DP_PASS) {
		$this->host = $host;
		$this->dbname = $dbname;
		$this->username = $username;
		$this->pass = $pass;
		self::connect();
	}

	private function connect() {
		try {
			$this->db = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->pass, array(
				PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8',
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_EMULATE_PREPARES => FALSE
			));
		} catch (PDOException $e) {
			die('Error: ' . $e->getMessage());
		}
	}

	public function insert($table, $form, $sql = null, $bool = true) {
		try {
			$place = $bool ? implode(', ', array_fill(0, count($form), '?')) : $sql;
			$column = implode(',', array_keys($form));
			$stmt = $this->db->prepare("INSERT INTO {$table} ({$column}) VALUES({$place})");
			$stmt->execute(array_values($form));
			return $this->db->lastInsertId();
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
			//die($e->getMessage());
		}
	}

	public function delete($table, $where) {
		try {
			$where2 = implode(' = ? && ', array_keys($where)) . ' = ? ';
			$stmt = $this->db->prepare("DELETE FROM {$table} WHERE {$where2}");
			$stmt->execute(array_values($where));
			return $stmt->rowCount();
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function update($table, $form, $where) {
		try {
			$column = implode(' = ?, ', array_keys($form)) . ' = ?';
			$where2 = implode(' = ? && ', array_keys($where)) . ' = ?';
			$stmt = $this->db->prepare("UPDATE {$table} SET {$column} WHERE {$where2}");
			$form = array_merge($form, $where);
			$stmt->execute(array_values($form));
			//print_r($form);
			//print_r(array_values($form));
			//echo " UPDATE {$table} SET {$column} WHERE {$where2}";
			return $stmt->rowCount();
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
			//die($e->getMessage() . " UPDATE {$table} SET {$column} WHERE {$where2}");
		}
	}

	public function sql($sql, $data = array(), $bool = true) {
		try {
			$stmt = $this->db->prepare($sql);
			for ($i = 0; $i < count($data); $i++) {
				$stmt->bindParam($i + 1, $data[$i], (is_int($data[$i]) ? PDO::PARAM_INT : PDO::PARAM_STR));
			}
			$stmt->execute();
			if ($bool === true) {
				return $stmt->fetchAll(PDO::FETCH_ASSOC);
			} else {
				return $stmt->rowCount();
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function beginTransaction() {
		$this->db->beginTransaction();
	}

	public function commit() {
		$this->db->commit();
	}

	public function rollBack() {
		$this->db->rollBack();
	}

	function __destruct(){
		$this->db = null;
	}
}

?>
