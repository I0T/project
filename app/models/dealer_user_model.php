<?PHP

// * $Id: dealer_user_model.php 24264 2014-07-08 07:15:59Z raohongfu $
//拍品相关

class Dealer_user_model extends YXP_Model {
    
    const TYPE_CAN_NOT_BID = 4;

	public function __construct() {
		parent::__construct('user', 'userid');
		$this->load->helper(array('array'));
	}
	

	/**
     * 查询用户数据
     * @param  array  $where  条件 
     * @param  array  $order_by order by
     * @param  array  $limit  limit
     * @param  string $result 返回类型data/数据 count/统计
     * @return array||int     返回数据或统计数
     * 
     */
	public function get_all_user($where, $order_by=array(), $limit=array(0,20), $result='data')
	{

		if($where)
		{	
			$this->db->where($where); 
		}

		if (is_array($order_by) && ! empty($order_by))
		{
			foreach ($order_by as $by_key => $by_val)
			{
				$this->db->order_by($by_key, $by_val);
			}
		}
		elseif ($order_by)
		{
			$this->db->order_by($order_by);
		}
		$this->db->select('dealer.*,dealer.name as dealername,user.*,user.name as names');
		$this->db->from('dealer');
		$this->db->join('user', 'user.dealer_id = dealer.dealerid' ,'left'); 

		if ($result == 'data')
		{
			if ($limit)
			{
				$this->db->limit($limit[1], $limit[0]);
			}
			return $this->db->get()->result_array();
		}
		else
		{
			return $this->db->count_all_results();
		}
	}

}
