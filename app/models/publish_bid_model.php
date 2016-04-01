<?PHP

//出价记录
class Publish_bid_model extends YXP_Model
{
	public function __construct()
	{
		parent::__construct('publish_bid' , 'bid');
	}

	/**
     * 买家车辆管理
     * @param  array  $where  搜索条件
     * @param  array  $limit  限制条件
     * @param  string $result 返回类型 'data'/数据 'count'/统计总数
     * @return array||int 
     * 
     */
	public function get_my_bid($where, $limit=array(), $result='data')
	{
		//组合where条件
		$tmp_arr = array();
		$tmp_arr['b.uid'] = $where['uid'];
    	//$tmp_arr['p.pisactive'] = 1;
		//拍品状态
		if($where['status']) {
			$tmp_arr['p.status'] = intval($where['status']);
		}
		//所在地		
		if($where['provinceid']) {
			$tmp_arr['c.prov_id']  = intval($where['provinceid']);
		}
		if($where['cityid']) {
			$tmp_arr['c.city_id']  = intval($where['cityid']);
		}
		//车型		
		if($where['brand_id']) {
			$tmp_arr['c.brand_id'] = intval($where['brand_id']);
        }
		if($where['trim_id']) {
			$tmp_arr['c.trim_id']  = intval($where['trim_id']);
		}
		if($where['series_id']) {
			$tmp_arr['c.series_id'] = intval($where['series_id']);
		}
		if($where['no']) {
			$this->db->like('c.no', $where['no']);
		}
		if($where['vin']) {
			$this->db->like('c.vin', $where['vin']);
		}
		//状态查询
		if($where['cstatus'])
		{
			if($where['cstatus']==1){//投标中
				$tmp_arr['p.bidding_status'] = 0;
			}
			elseif($where['cstatus']==2){//竞价中
				$tmp_arr['p.bidding_status'] = 2;
			}
			elseif($where['cstatus']==3){//等待竞价
				$tmp_arr['p.bidding_status'] = 1;
			}
			elseif($where['cstatus']==4){//成交
				$tmp_arr['p.status'] = 3;
			}
			elseif($where['cstatus']==5){//流拍
				$tmp_arr['p.status'] = 2;
			}

		}

		$this->db->select('b.bid,b.type,b.publish_id,b.price,b.total_price,p.start_price,p.car_id,p.publishid,p.publish_bid,p.top_price,p.start_time,p.end_time,p.status,p.pn,p.bid_time,p.bidding_status,c.hy_carid,c.no,c.vin,c.car_name,c.chake_task_id,c.source_from')
			 ->where($tmp_arr)
			 ->from('publish_bid b');
			 
		//我的成交
        if($where['status']==3)
        {
        	$this->db->join('publish p','p.publish_bid=b.bid');
        }
        else
        {
        	$this->db->join('publish p','p.publishid=b.publish_id');
        	$sql= 'b.bid in(select max(`bid`) from (`publish_bid`) where `uid`='.$where['uid'].' group by `publish_id`)';
        	$this->db->where($sql);
        }

        $this->db->join('car c','p.car_id=c.carid');
        $this->db->order_by('p.end_time','desc');

        if($result == 'data')
        {
        	if($limit)
        	{
        		$this->db->limit($limit[1],$limit[0]);
        	}
        	
        	return $this->db->get()->result_array();

        }
        elseif ($result == 'count')
        {
			return $this->db->get()->num_rows();
		}
	}

	/**
     * 查询拍品出价记录
     * @param  string $publishid  拍品id
     * @return array  $data
     * 
     */
	public function record($publishid=0)
	{	
		$publishid = intval($publishid);
		if($publishid)
		{	
			$data = $this->db->select('b.*,b.createtime as bcreatetime,c.*,p.start_time as pstart_time,
					p.createtime as pcreatetime,p.car_id,p.pn,d.name as dealer_name,u.userid')
				->join('publish_bid b', 'p.publishid = b.publish_id')
				->join('dealer d', 'd.dealerid = b.dealer_id', 'left')
				->join('user u', 'u.dealer_id = d.dealerid', 'left')
				->join('car c', 'c.carid = p.car_id', 'left')
				->where(array('p.publishid' => $publishid))
				->order_by('b.bid','desc')
				->get('publish p')
				->result_array();

		    $this->load->model('area_model');
			$area = $this->area_model->get_bigarea_province_by_city($data[0]['city_id']);
			foreach($data as $k => $v)
			{
				$data[$k]['area_name'] = $area['bigareaname'];
				$data[$k]['prov_name'] = $area['provincename'];
				$data[$k]['city_name'] = $area['cityname'];
			}
			return $data;
		} 

		return;

	}

}
