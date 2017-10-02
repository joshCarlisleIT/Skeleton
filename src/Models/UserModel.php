<?php
namespace IngeniousWeb\Skeleton\Models;

use IngeniousWeb\Skeleton\Services\Storage\DB;

class UserModel
{
	/**
	 * @var $db/
	 */
	private $db;

	/**
	 * @param DB $db
	 */
	public function __construct(DB $db)
	{
		$this->db = $db;
	}

	public function get($table = null, $where = [])
	{
		$data = $this->db->get($table, $where);
		if ($this->db->count()) {
			$data = $this->db->first();
		}

		return $this->data = (object) [
			'user' => $data
		];
	}

	public function getSession($table = null, $where = [], $id, $hash)
	{
		$data = $this->db->get($table, $where);
		if (!$this->db->count()) {
			$this->db->insert('userSession', [
				'userID' => $id,
				'hash' => $hash
			]);
		} else {
			$hash = $this->db->first()->hash;
		}
	}

	public function insert($table = null, $where = [])
	{
		return $this->db->insert($table, $where);
	}

	public function update($table, $where, $data)
	{
		return $this->db->update($table, $where, $data);
	}

	public function updatePass($table, $where, $data)
    {
        return $this->db->update($table, $where, $data);
    }

	public function delete($table = null, $where = [])
	{
		return $this->db->delete($table, $where);
	}
}
