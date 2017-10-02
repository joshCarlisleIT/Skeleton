<?php
namespace IngeniousWeb\Skeleton\Services\Storage;

use \PDO;

class DB
{
	/**
	 * @var \pdo
	 */
	private $pdo;
	 
	/**
	 * DB constructor.
	 * @param $conn
	 * @param $username
	 * @param $password
	 */
	public function __construct($conn, $username, $password)
	{
		try {
			$this->pdo = new \PDO($conn, $username, $password);
		} catch (\PDOException $e) {
			throw $e;
		}
	}

	public function action($action, $table, $where = [], $extra = '')
	{
		$operators = ['=', '>', '<', '>=', '<='];

		$sql = "{$action} FROM {$table}";

		if (!is_null($where) && !empty($where)) {
			if (!isset($where[0])) {
				throw new \Exception("Expecting '{$where[0]}' to exist");
			}
			$field = $where[0];
			$operator = $where[1];
			$value = $where[2];


			if (!in_array($operator, $operators)) {
				throw new \Exception('Passed operator "' . $operator . '". Should be in ' . implode(', ', $operators));
			}

			$sql .= " WHERE {$field} {$operator} ?";
			$sql .= " {$extra}";
			$this->query($sql, [$value]);
		} else {
			$sql .= " {$extra}";
			$this->query($sql);
		}
	}

	public function query($sql, $params = [])
	{
		if ($query = $this->pdo->prepare($sql)) {
			try {
				$errorInfo = $query->errorInfo();
				if (!$query->execute($params)) {
					$e = new \PDOException("Error with query: {$sql}");
					$e->errorInfo = $errorInfo;
					throw $e;
				}
				$this->results = $query->fetchALL(PDO::FETCH_OBJ);
				$this->count = $query->rowCount();
			} catch (\PDOException $e) {
				$error = true;
				throw $e;
			}
			return $this->results;
		}
		throw new \Exception("I goofed");
	}

	public function get($table = '', $where = '')
	{
		return $this->action('SELECT *', $table, $where);
	}

	public function getLast($table, $where)
	{
		return $this->action('SELECT *', $table, $where = null, 'ORDER BY id DESC LIMIT 1');
	}

	public function delete($table, $where)
	{
		return $this->action('DELETE', $table, $where);
	}

	public function insert($table, $fields = [])
	{
		$keys = array_keys($fields);
		$values = [];

		foreach ($keys as $value) {
			$values[] = "?";
		}

		$sql = "INSERT INTO {$table} (`".implode('`, `', $keys)."`) VALUES (".implode(", ", $values).")";
		if (!$this->query($sql, array_values($fields))) {
			return true;
		}
		return false;
	}

	public function update($table, $ids, $fields)
	{
		$set = [];
		$where = [];
		$params = [];

		foreach ($fields as $name => $value) {
			$set[] = "{$name} = ?";
			$params[] = $value;
		}

		foreach ($ids as $name => $id) {
			$where[] = "{$name} = ?";
			$params[] = $id;
		}

		$sql = "UPDATE {$table} SET ".implode(', ', $set)." WHERE ".implode(" OR ", $where)."";
		if (!$this->query($sql, $params)) {
			return true;
		}
		return false;
	}

	public function results()
	{
		return $this->results;
	}

	public function first()
	{
		return $this->results()[0];
	}

	public function lastID()
	{
		return $this->lastID = $this->pdo->lastInsertID();
	}

	public function error()
	{
		return $this->error;
	}

	public function count()
	{
		return $this->count;
	}
}