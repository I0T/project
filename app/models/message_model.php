<?PHP

// * $Id: message_model.php 24264 2014-07-08 07:15:59Z raohongfu $
//拍品相关

class Message_model extends YXP_Model {
	public function __construct() {
		parent::__construct('message', 'messageid');
	}

	/**
     * 卖家管理查询用户通知数据
     * @param  array  $where  条件 
     * @param  array  $order_by order by
     * @param  array  $limit  limit
     * @param  string $result 返回类型data/数据 count/统计
     * @return array||int     返回数据或统计数
     * 
     */
	public function get_message_list($where, $order_by=array(), $limit=array(0,20), $result='data')
	{

		if ($where)
		{
			$this->db->where($where); 
		}
		if (is_array($order_by) && ! empty($order_by)) {
			foreach ($order_by as $by_key => $by_val) {
				$this->db->order_by($by_key, $by_val);
			}
		}
		elseif ($order_by)
		{
			$this->db->order_by($order_by);
		}
		
		if ($result == 'data')
		{
			if ($limit)
			{
				$this->db->limit($limit[1], $limit[0]);
			}
			return $this->db->get('message')->result_array();
		}
		else
		{
			return $this->db->count_all_results('message');
		}
	}

}
