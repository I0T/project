<?PHP

// * $Id: dealer_model.php 25732 2014-08-05 08:05:48Z jinlong $
//拍品相关

class Dealer_model extends YXP_Model {

	public function __construct() {
		parent::__construct('dealer', 'dealerid');
		$this->load->helper(array('array'));
	}
	
	/**
     * 查询用户数据
     * @param  array  $where  条件 
     * @param  array  $limit  limit
     * @param  string $result 返回类型data/数据 count/统计
     * @return array||int     返回数据或统计数
     * 
     */
	public function get_all_dealer($where, $limit=array(), $result='data')
	{

		$tmp_arr   = array();
		$tmp_arr['type !='] = 0;
		if($where['city_id'])
		{
			$tmp_arr['d.city_id'] = $where['city_id'];
		}
		if($where['pro_id'])
		{
			$tmp_arr['d.pro_id']  = $where['pro_id'];
		}
		if($where['dealername'])
		{	
			$dealername = $this->db->escape_like_str($where['dealername']);
			$this->db->like(array('d.name' => $dealername));
		}

		$this->db->select('*,u.name as uname')
			 ->join('dealer d','u.dealer_id = d.dealerid')
			 ->where($tmp_arr)
			 ->order_by('u.createtime', 'desc');

		if($result == 'data')
		{
			if( ! empty($limit))
			{
				$this->db->limit($limit[1], $limit[0]);
			}

			return $this->db->get('user u')->result_array();

		}
		elseif($result == 'count')
		{
			return $this->db->get('user u')->num_rows();
		}
	}

}
