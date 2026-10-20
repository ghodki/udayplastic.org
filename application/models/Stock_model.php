
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_model extends CI_Model
{
	/** @var CI_DB_query_builder|object|null */
	public $db2;

	public function __construct()
	{
		parent::__construct();
		$this->db2 = $this->load->database('store_umatic', TRUE);
	}

	public function getsecondUsers()
	{
		$query = $this->db2->get('user');
		return $query->result();
	}
	public function saveAry($table, $data)
	{
		$this->db2->insert($table, $data);
		return $this->db2->insert_id();
	}

	public function getAry($table, $order = [], $start = '', $limit = '', $where = '', $select = '')
	{

		if ($select != '') {
			$this->db2->select($select);
		}
		if ($where != '') {
			$this->db2->where($where);
		}

		//['col'=>'id','order'=>'ASC']
		// if($order != ''){ 
		//     $this->db->order_by($order['col'],$order['order']);
		// }


		if ($start != '' || $limit != '') {
			$this->db2->limit($limit, $start);
		}
		// $this->db->order_by("id", "DESC");

		return $this->db2->get($table)->result();
		// echo $this->db->last_query();die;

	}

}
?>