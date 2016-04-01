<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

//yxp model基类
class YXP_Model extends CI_Model {

	//表名
	private $_ytable;

	//表主键
	private $_yid;

	// Constructor
	public function __construct($table = '', $id = '') {
		$this->_ytable = $table;
		$this->_yid = $id;

		parent::__construct();
		$this->load->database();
	}

	//通用方法 保存信息
	public function save($data) {
		if ($this->db->insert($this->_ytable, $data)) {
			$affect_status = $this->db->insert_id();
			//insert_id,数据表如果没有自增id会返回0
			if ($affect_status) {
				return $affect_status;
			} else {
				return $this->db->affected_rows();
			}
		}
	}

	//批量插入
	public function save_batch($data) {
		if($data){
			$this->db->insert_batch($this->_ytable,$data);
			return $this->db->affected_rows();
		}
		return 0;
	}

	//通用方法 更新信息
	public function update($data, $where) {
		$this->db->update($this->_ytable, $data, $where);
		return $this->db->affected_rows();
	}

	//批量更新
	public function update_batch($data, $key) {
		$this->db->update_batch($this->_ytable, $data, $key);
		return $this->db->affected_rows();
	}

	/**
	 * 通过主键id更行数据信息
	 * @param array 更新的信息
	 * @param int 主键id
	 * @return int 返回影响行数
	 */
	public function update_by_id($data,$id) {

	return	$this->update($data, array("$this->_yid" => $id));
	}

	//通用方法 根据ID获取信息
	function get_by_id($id, $field = '*') {
		return $this->get_one($id, $field);
	}

	public function get_one($id, $field = "*") {
		if (is_array($field)) {
			$field = implode(', ', $field);
		}
		if (is_array($id)) {
			$where = " WHERE `{$this->_yid}` IN ('" . join("', '", $id) . "')";
			$tmp = $this->db->query("SELECT " . $field . "," . $this->_yid . " FROM `{$this->_ytable}` {$where}")->result_array();
			$resp = array();
			foreach ($tmp as $v) {
				$resp[$v[$this->_yid]] = $v;
			}
			return $resp;
		}
		return $this->db->query("SELECT " . $field . "," . $this->_yid . " FROM `{$this->_ytable}` WHERE `$this->_yid` = " . intval($id))->row_array();
	}

	//通用方法 根据条件获取数据
	public function find($search, $order_by = '', $asc = 'ASC', $limit = 0) {
		return $this->get_all($search, $order_by = '', $asc = 'ASC', $limit = 0);
	}

	//通用方法 根据条件获取数据
	public function get_all($search, $order_by = '', $asc = 'ASC', $limit = 0) {
		$where = ' WHERE 1 ';
		foreach ($search as $k => $v) {
			if (is_array($v)) {
				$where .= " AND `{$k}` IN ('" . join("', '", $v) . "')";
			} elseif (substr($k, -5) == '_like') {
				$k = str_replace('_like', '', $k);
				$where .= " AND `{$k}` LIKE '%" . $this->db->escape_like_str($v) . "%'";
			} elseif (substr($k, -6) == '_isnot') {
				$k = str_replace('_isnot', '', $k);
				$where .= " AND `{$k}` != '" . $this->db->escape_like_str($v) . "'";
			} else {
				$where .= " AND `{$k}` = " . $this->db->escape($v);
			}
		}
		$order_by = $order_by ? $order_by : $this->_yid;
		$asc = strtolower($asc) == 'asc' ? 'ASC' : 'DESC';
		if (is_numeric($limit)) {
			$limit = $limit ? 'LIMIT ' . intval($limit) : '';
		} else if (is_array($limit)) {
			$limit = $limit ? "limit " . join(',', $limit) : '';
		} else if (is_string($limit)) {
			$limit = $limit ? 'LIMIT ' . $limit : '';
		}
		$sql = "SELECT * FROM `{$this->_ytable}` {$where} ORDER BY `{$order_by}` {$asc} {$limit}";
		//echo $sql;

		return $this->db->query($sql)->result_array();

	}

	//统计行数
	public function get_count($search = array()) {
		return count($this->get_all($search, $order_by = '', $asc = 'ASC', $limit = 0));
	}

	//通用方法 根据条件获取单条
	public function get_row($search, $order_by = '', $asc = 'ASC', $limit = 0) {
		$res = $this->get_all($search, $order_by, $asc, 1);
		return $res && is_array($res[0]) ? $res[0] : array();
	}

	//通用方法 获取某列
	public function get_col($search, $field) {
		$resp = $this->get_all($search);
		$ret = array();
		if ($resp) {
			foreach ($resp as $res) {
				$ret[$res[$this->_yid]] = $res[$field];
			}
		}
		return $ret;
	}

	//通用方法 在不方便的时候手动执行sql
	public function query($sql) {
		$this->db->query($sql);
		return $this->db->affected_rows();
	}

	//通用方法 删除
	public function delete($id) {
		$sql = "DELETE FROM `{$this->_ytable}` WHERE 1 ";
		if (is_array($id)) {
			foreach ($id as $k => $v) {
				$sql .= " AND `{$k}` = " . $this->db->escape($v);
			}
		} else {
			$sql .= " AND `{$this->_yid}` = " . intval($id);
		}
		$this->db->query($sql);
		return $this->db->affected_rows();
	}

	//打印调试
	public function p($data)
	{
		echo '<pre>';
		print_r($data);
		echo '<pre>';
		exit;
	}

}

// END Controller class

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */
