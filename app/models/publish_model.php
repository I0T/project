<?PHP

// * $Id: publish_model.php 26517 2014-08-21 02:31:39Z jinlong $
//拍品相关

class Publish_model extends YXP_Model {
    
	public function __construct() {
		parent::__construct('publish', 'publishid');
		$this->load->model(array('car_model'));
		$this->load->helper(array('array'));
		//yxp_cp库
	}

	/**
     * 卖家交易管理界面搜索
     * @param  array  $where  条件 
     * @param  array  $limit  限制条数 
     * @param  string $result 返回类型'data'/数据 'count'/总条数 
     * @return array || int
     * 
     */
	public function search($where, $limit=array(), $result='data')
	{
		//组合条件
		$this->build_search_sql($where, 1);
		$this->db->from('publish p');
		$this->db->join('car c', 'p.car_id=c.carid');
		$this->db->join('dealer d', 'd.dealerid=c.dealer_id');
        $this->db->join('publish_bid b', 'p.publish_bid=b.bid', 'left');  
		if($result == 'count')
		{
			return $this->db->count_all_results();
		}
        if ($limit)
        {
            $this->db->limit($limit[1],$limit[0]);
        }
		if($result == 'data')
		{
			$this->db->select('b.total_price,p.*,c.*,p.status as pstatus,d.dealerid,d.name');
			$data = $this->db->get()->result_array();
			return $data;
		}
	}

	
	/**
     * 拍卖大厅搜索
     * @param  array  $where  条件 
     * @param  array  $limit  限制条数 
     * @param  string $result 返回类型'data'/数据 'count'/总条数 
     * @return array || int
     * 
     */
	public function search_bid($where, $limit=array(), $result='data')
	{
		
		//组合where条件
		$this->build_search_sql($where, 2);
		$res = $this->db->select('p.current_row as rows')
					->from('publish p')
					->join('car c', 'p.car_id=c.carid','left')
					->join('dealer d', 'd.dealerid = c.dealer_id','left')
					->group_by('p.current_row')
					->order_by('p.current_row asc')
					->get();

		if($result == 'count')
		{
			return $res->num_rows();//总行数
		}

		if($result == 'data')
		{	
			$rows = $res->result_array();
			$arr_rows = array();
			foreach ($rows as $key => $val) 
			{
				$arr_rows[$key+1] = $val['rows'];
			}

			if(empty($arr_rows))
			{
				$arr_rows = array(1);
			}
			$limit[0] = $arr_rows[$limit[0]] ? $arr_rows[$limit[0]] : 0;
			$limit[1] = $arr_rows[$limit[1]] ? $arr_rows[$limit[1]] : max($arr_rows);

			$this->build_search_sql($where,2);
			return $this->db->where("p.current_row between $limit[0] and $limit[1]")
					->select('p.*,c.*,d.dealerid,d.name,p.status as pstatus')
					->from('publish p')
					->join('car c', 'p.car_id=c.carid','left')
					->join('dealer d', 'd.dealerid = c.dealer_id','left')
					->order_by('p.bid_time asc, p.order asc')
			        ->get()->result_array();
		}

	}


	//维护竟价中
	public function bidding_status() {
		
		//竞价排队      bidding_status =2正在竞价，=1排队竞价
		$time     = time();
		$time60   = time()+60;
		$bid_time = Date('YmdH', $time);// 当前场次
		
		//到竞价场次时间的车 都先改为排队加上60秒
		$this->db->set('end_time', '`end_time`+60', FALSE);
		$this->update(array('bidding_status' =>1), array('status' =>1 ,'bid_time' =>$bid_time, 'bidding_status' =>0));
		//正在竞价的车
		$biding = $this->db->select('publishid')->get_where('publish', array('status' =>1, 'bidding_status' =>2), 1)->row_array();
		if(!$biding)
		{//如果没有正在竞价的车，将等待竞价的第一辆车改为正在竞价
			$sql = "update publish a,( select publishid from publish where status=1 and bidding_status =1 and end_time<='{$time60}' order by end_time asc, `order` asc limit 0,1) b set a.bidding_status=2 where a.publishid=b.publishid ";
			$this->db->query($sql);
		}

		//超过时间的车
		$publish = $this->db->select('publishid,order,order,current_row,price,pre_bid_id,pre_bid_price,top_price,bid_time')
				->where(array('status' =>1, 'bidding_status <' =>3, 'end_time <' =>$time))
				->order_by('current_row desc, order desc')
		    	->get('publish');
		$current_publish   = $publish->row_array();
		$total_publish 	   = $publish->result_array();
		$publish->free_result();

		if($current_publish)
		{	
			//当前拍品位置
			$position = $this->db->get_where('publish', array('status' =>1, 'current_row' => $current_publish['current_row'], 'order <=' => $current_publish['order']))->num_rows();
			if($position == 4)
			{	
				$reduce = $current_publish['current_row']-1;
				if($reduce > 0)
				{	
					$this->db->set('current_row', "`current_row`-$reduce", FALSE);
					$this->update(array(),array('status' =>1, 'current_row >=' =>$current_publish['current_row']));
				}

				$this->db->set('current_row', '`current_row`-1', FALSE);
				$this->db->where(array('status' =>1, 'bid_time >=' =>$current_publish['bid_time']));
				$order0 = $current_publish['order']%4;
				$order1 = ($current_publish['order']-1)%4;
				$order2 = ($current_publish['order']-2)%4;

				if($position == 1)
				{
					$this->db->where("`order`%4","$order0", FALSE);
				}
				elseif($position == 2)
				{	
					$this->db->where("`order`%4 in ($order0, $order1)", NULL, FALSE);
				}
				elseif($position == 3)
				{	
					$this->db->where("`order`%4 in ($order0, $order1, $order2)", NULL, FALSE);
				}

				$this->db->update('publish');

				//把后面的其他场次页数更新
				$max_order = $this->db->select_max('order','max_order')
				     ->where("status =1 and bid_time={$current_publish['bid_time']}")
				     ->get('publish')
				     ->row_array();

				if($current_publish['order']%4 != $max_order['max_order']%4)
				{
					$this->db->set('current_row', '`current_row`-1', FALSE)
						 ->where('bid_time >' , $current_publish['bid_time'])
						 ->update('publish');
				}
			}
			else
			{	
				$reduce = $current_publish['current_row'];
				$this->db->set('current_row', "`current_row`-$reduce", FALSE)
						 ->where("status =1 and current_row >={$current_publish['current_row']}")
						 ->update('publish');
			}

			$arr_data = array();
			foreach ($total_publish as $key => $val)
			{
				
				$arr_data[$key] = array(
					'publishid' 	=> $val['publishid'],
					'current_row' 	=> 0,
					'bidding_status'=> 3
				);

				if($val['pre_bid_price'] >= $val['price'] && $val['pre_bid_price'] >= $val['top_price'])
				{//投标成交
					$arr_data[$key]['publish_bid'] = $val['pre_bid_id'];
					$arr_data[$key]['top_price']   =  $val['pre_bid_price'];
					$arr_data[$key]['status'] 	   = 3;
					 
				}
				elseif ($val['top_price'] >= $val['price']) 
				{//竞价成交
					$arr_data[$key]['status'] = 3;

				}
				elseif ($val['top_price'] < $val['price'] && $val['pre_bid_price'] < $val['price'])
				{//流拍
					$arr_data[$key]['status'] = 2;
				}
				
			}
			//改状态
			$this->update_batch($arr_data, 'publishid');
			unset($arr_data);	
		}	

		
	}

	/**
     * 获取竞价记录参与人数
     * @param  int  $pid      拍品id
     * @param  int  $topbid   出价最高价id
     * @param  int  $limit    展示条数
     * @return array
     * 
     */
    public function get_bid_list($pid=0, $topbid=0, $limit=0)
    {
    	
    	$where = array('type' => 2);
        if(!$pid)
        {
        	return;
        }
        else
        {
        	$where['publish_id'] = $pid;
        }

        if($topbid)
        {
        	$where['bid <='] = $topbid;//加上这个条件，是为了和上面获取的最高价格一致
        }
        if($limit)
        {
        	$this->db->limit($limit);
        }

        $this->db->order_by('bid','DESC');
        $this->db->where($where);
        $bid_list = $this->db->get('publish_bid')->result_array();

        if($bid_list)
        {
            $this->load->model(array('user_model'));                
            $uid = array_muliti_field($bid_list,"uid");
            $user_data = $this->user_model->get_one($uid);
            foreach ($bid_list as $key => $value)
            {
                if($user_data[$bid_list[$key]['uid']]['type'] == 1)
                {
                    $user_mobile_erp = $user_data[$bid_list[$key]['uid']]['userid'];
                }
                else
                {
                    $user_mobile_erp=$user_data[$bid_list[$key]['uid']]['mobile'];
                }                 
                $bid_list[$key]['user_mobile_erp'] = strlen($user_mobile_erp) > 4 ? "****".substr($user_mobile_erp, -4) : (strlen($user_mobile_erp) > 2 ? "******".substr($user_mobile_erp, -2) : $user_mobile_erp);
                $bid_list[$key]['time_show']	   = date("Y-m-d H:i:s", $bid_list[$key]['createtime']);
            }

        }  

        $online = $this->db->where($where)
        		->group_by('uid')
        		->get('publish_bid')
        		->num_rows();

        return array('bid_list' => $bid_list, 'bid_online' => $online);
    }

    /**
     * 
     * @param array $arr   array(array('type' => 'trim|model|serial','key'=>'carmodelid|fld_serialid|fld_serialid',value=''))
     * @return type
     * @throws Exception
     * 
     */
    public function get_car_type($arr)
    {
        $this->load->config("dict");
        $uri     = $this->config->item("dict_domain_padapi");
        $urlCase = $uri . 'api_car/cxk_brand_series_mode/?type=%s&%s=%s';
        $result  = array();
        
        foreach ($arr as $row)
        {
            $url = sprintf($urlCase, $row['type'], $row['key'], $row['value']);
            $str = file_get_contents($url);
            $res = json_decode($str, true);
            if (!$res)
            {
                throw new Exception('不存在此类型的数据:type='.$row['type'].'&'.$row['key'].'='.$row['value']);
            }
            $result[$row['type']] = $res;
        }
        return $result;
    }


    /**
     * 查询条件组合
     * @param  array  $where  条件 
     * @param  int $flag   类型1/search 2/search_bid 
     * @return void
     * 
     */
    public function build_search_sql($where, $flag)
    {
    	$tmp_arr = array();
    	$tmp_arr['p.pisactive'] = 1;
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
		if($where['area_id']) {
			$tmp_arr['c.area_id']  = intval($where['area_id']);
		}
		if($where['provinceid']) {
			$tmp_arr['c.prov_id']  = intval($where['provinceid']);
		}
		if($where['cityid']) {
			$tmp_arr['c.city_id']  = intval($where['cityid']);
		}
		if($where['no']) {
			$this->db->like('c.no', $where['no']);
		}
		if($where['vin']) {
			$this->db->like('c.vin', $where['vin']);
		}
		
		if($flag == 1)
		{

			//拍品状态
	        $tmp_arr['p.status'] = intval($where['status']);
			if($where['status']==2 || $where['status']==3)
			{
				$this->db->order_by("p.end_time", "desc"); 
			}
			else
			{
				$this->db->order_by("p.end_time", "asc"); 
			}
			
		}
		elseif($flag == 2) 
		{
			//拍品状态
	        $tmp_arr['p.status'] = intval($where['status']);
		}

		$this->db->where($tmp_arr);
		unset($tmp_arr);
    }
    
}
