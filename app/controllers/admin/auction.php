<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 拍卖执行人 发起拍卖 * $Id: auction.php 26013 2014-08-08 02:13:01Z jinlong $

class Auction extends YXP_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('array');
		$this->load->model(array('user_model','dealer_model','car_model','publish_model'));
		$this->load->config('dict');
	}

	// 拍卖管理
	public function index($page=1) {

        $this->smarty->assign('login_info',$_SESSION['user']);
		$this->smarty->display('admin/auction.html');
	}


	// 导入查克报告
	public function import() {

		$get = $this->input->get();
		$this->load->model('cp_car_info_model', 'carinfo');
		$car['user'] 		= $this->user_model->login_info();
		$car['dealer_info'] = $this->user_model->get_dealer_info($car['user']['userid']);

		if($get['hy_carid'])
		{
			$car_info = $this->carinfo->get_chake_carinfo(intval($get['hy_carid']));
			//图片
			$img_path = $this->carinfo->get_img_path($car_info['taskid']);
			if($img_path)
			{
				$car_info['car_base_img'] = $img_path;
			}
			$this->smarty->assign('page',$get['page']);
			$car['carid'] = $get['car_id'];
			$this->smarty->assign("hy_carid",$get['hy_carid']);
			$this->smarty->assign('car',$car);
			$this->smarty->assign('car_info',$car_info);
        	$this->smarty->assign('login_info',$_SESSION['user']);
        	$this->smarty->assign('dict_domain_chake', $this->config->item('dict_domain_chake'));
			$this->smarty->display('admin/auction_create_import.html');
		}
		else
		{
			$per_page  = 10;
			$page      = max(1, $get['page']);
			$limit     = array(($page-1)*$per_page, $per_page);
			$tvaid     = $this->config->item('dict_chrysler_'.$car['user']['dealer_id']);
			$get['tvaid'] = $tvaid;
			$count = $this->carinfo->get_detail_carinfo($get, $limit, 'count');
			$cars  = $this->carinfo->get_detail_carinfo($get, $limit, 'data');
			$this->load->config('page', true);
			$config = $this->config->item('page');
			$config['page_query_string'] = TRUE;
			$config['total_rows'] = $count > 0 ? $count : 0;
			unset($get['page']);
			$config['base_url'] = '?'.http_build_query($get);
			$this->pagination->initialize($config);
			$links = $this->pagination->create_links();
			$this->smarty->assign('links', $links);
            $ford  = array();
            if ($get['brand_id'] && $get['series_id'])
            {
                try {
                    $params = array(
                        array('type' => 'brand', 'key' => 'iautos_brand_id',  'value' => $get['brand_id']),
                        array('type' => 'series','key' => 'iautos_series_id', 'value' => $get['series_id']),
                        array('type' => 'mode',	 'key' => 'iautos_series_id', 'value' => $get['series_id']),
                    );
                    $result    = $this->publish_model->get_car_type($params);
                    $seriesYes = false;
                    $seriesRes = array();
                    foreach ($result['series'] as $series => $ser)
                    {
                        foreach ($ser as $s)
                        {
                            if ($s['iautos_series_id'] == $get['series_id'])
                            {
                                $seriesRes = $s;
                                $seriesYes = true;
                                break;
                            }
                        }
                        if ($seriesYes)
                        {
                            break;
                        }
                    }
                    $modeYes = false;
                    $modeRes = array();
                    foreach ($result['mode'] as $modes)
                    {
                        foreach ($modes as $m)
                        {
                        	if (intval($m['iautos_mode_id']) == intval($get['trim_id']))
                        	{
                        		$modeRes = $m;
                                $modeYes = true;
                                break;
                        	}
                        }
                        if ($modeYes)
                        {
                            break;
                        }
                    }
                    $ford = array(
                        'serial_name' => strval($result['brand']['iautos_brand_name']),
                        'model_name'  => strval($seriesRes['iautos_series_name']),
                        'trim_name'   => strval($modeRes['cxk_mode_name']),
                        'brand_id' 	  => $get['brand_id'],
                        'series_id'   => $get['series_id'],
                        'trim_id' 	  => $get['trim_id'],
                    );
                    
                    $this->smarty->assign('ford', $ford);
                } 
                catch (Exception $e)
                {
                    
                }
            }

            $this->smarty->assign('ford', $ford);
			$this->smarty->assign('get',$get);
			$this->smarty->assign('page',$page);
			$this->smarty->assign('dict_domain_chake', $this->config->item('dict_domain_chake'));
			$this->smarty->assign('cars',$cars);
       		$this->smarty->assign('login_info',$_SESSION['user']);
			$this->smarty->display('admin/auction_import.html');
		}
	}

	//保存查克报告并发拍
	public function import_post() {

		$post = $this->input->post();
		if(! $post){
			$this->ajax_return(array('status' => 0,'msg' => '操作失败'));
		}
		$exists = $this->car_model->get_exists_bidding(intval($post['hy_carid']));
        if ($exists) 
        {
            //show_error('对不起，该车辆已在发拍中！', 200, '操作失败');
            $this->ajax_return(array('status' => 0,'msg' => '对不起，该车辆已在发拍中!'));
        }

		$user = $this->user_model->login_info();
		$time = time();
		$post['carid'] = intval($post['carid']);
        
		//基础数据
		$ctmp = array(
			'dealer_id' => intval($user['dealer_id']),
			'car_name' 	=> trim($post['car_name']),
			'vin' 		=> trim($post['chake_vin']),
			'hy_carid'	=> intval($post['hy_carid']),
			'brand_id' 	=> intval($post['brand_id'])+2000000000,
			'series_id'	=> intval($post['series_id'])+2000000000,
			'trim_id'	=> trim($post['trim_id'])+2000000000,
			'no'		=> trim($post['no']),
			'prov_id'	=> trim($post['prov_id']),
			'city_id'	=> trim($post['city_id']),
			'chake_task_id'	=> intval($post['chake_task_id']),
			'regist_time'	=> trim($post['regist_time']),
			'shows_mileage'	=> trim($post['shows_mileage']),
			'color' 		=>	trim($post['color']),
			'car_base_img'  => $post['car_base_img'],
		);
        $ctmp['source_from'] = $post['source_from'] < 3 ? 2 : 3;

		$this->db->trans_begin();

		if($post['carid']) 
		{ //流拍重发拍
			$car_id = $post['carid'];
			$ctmp['updatetime'] = $time;
			$this->car_model->update($ctmp, array('carid'=>$car_id));
			$this->publish_model->update(array('pisactive'=>0),array('car_id'=>$car_id));
		} 
		else 
		{ //新增
			$ctmp['createtime'] = $ctmp['updatetime'] = $time;
			$car_id = $this->car_model->save($ctmp);
		}

		if(!$car_id)
		{
			$this->db->trans_rollback();
			$this->ajax_return(array('status' =>0,'msg' => '对不起，新增车辆失败!'));
		}

		$ptmp = array(
			'car_id' 	  => $car_id,
			'price' 	  => floatval($post['price']),
			'start_price' => floatval($post['start_price']),
			'createtime'  => $time,	
			'status' 	  => 1,
		);

        $day = $post['auction_day'];
        $bid_time  = strtotime($day." ".$post['end_time'].":00:00");
        $now_day   = intval(date('d', $time));
        $bid_day   = intval(date('d', $bid_time));
        $bid_hour  = intval(date('H', $bid_time));
        $now_hour  = intval(date('H', $time));

        if($bid_day == $now_day) 
        {
        	if($now_hour > 17 || $now_hour > ($bid_hour-2)) 
        	{
        		$this->ajax_return(array('status' =>0,'msg' => '对不起，时间超时新增拍品失败!'));
        	}
        }

        if(($bid_time - $time) > 126800) 
        {
        	$bid_time   = $bid_time-86400;
        	$start_time = strtotime(date('Y-m-d',$bid_time)." "."00:00:00");
        }
        else
        {
        	$start_time = $time;
        }
        
        $ptmp['start_time'] = $start_time;
		$ptmp['end_time'] = strtotime($day." ".$post['end_time'].":00:00");
        $ptmp['bid_time'] = date('YmdH',$ptmp['end_time']);
        $order = $this->db->get_where('publish', array('bid_time' => $ptmp['bid_time'], 'pisactive' => 1))->num_rows();
		$row_sql 	= "SELECT sum(`b`.`row`) as 'rows' FROM (SELECT  ceil(count(1)/4) as 'row' FROM publish where `status`=1 and `bid_time`<='{$ptmp['bid_time']}' group by `bid_time`) as b";
		//$row_sql  = "select `publishid` from publish where `status`=1 and `bid_time`<='{$ptmp['bid_time']}' group by `current_row`";
		//$rows 	= $this->db->query($row_sql)->num_rows();//总行数
		$rows 	    = $this->db->query($row_sql)->row_array();
		$ptmp['end_time'] = strtotime($day." ".$post['end_time'].":00:00") + $order*120;
		$publish_order    = $order + 1;
		$ptmp['order'] 	  = $publish_order;
		$pn 			  = $publish_order<10 ? "0{$publish_order}" : $publish_order;
		$ptmp['pn'] 	  = $ptmp['bid_time'].$pn;//拍品编号

		if($publish_order%4 == 1)
		{
			$ptmp['current_row'] = $rows['rows']+1;
			$flag = 1;
		}
		else
		{
			$ptmp['current_row'] = $rows['rows'];
		}

		$pid = $this->publish_model->save($ptmp);

		if($pid) 
		{	
			if($flag == 1)
			{
				$this->db->set('current_row', '`current_row`+1', FALSE)
					 ->where(array('status' => 1, 'bid_time >' => $ptmp['bid_time']))
					 ->update('publish');
			}
			$this->db->trans_commit();
			$this->ajax_return(array('status' => 1, 'msg' => '发拍成功!', 'pn'=> $ptmp['pn']));
		}
		else
		{
			$this->db->trans_rollback();
			$this->ajax_return(array('status' => 0, 'msg' => '对不起，新增拍品失败!'));
		}
	}

    public function send_sms(){
        $get = $this->input->get();
        header("Content-Type:text/html; charset=utf-8");
        if (!$get['mobile'] || !$get['content']) {
            echo '请检查参数是否齐全';exit;
        }
        $conf = array(
                'accesskey' => 'YXP_DKH',
                'mobile' => $get['mobile'],
                'content' => $get['content']."[优信拍]",
        );
        ksort($conf);
        $sn = md5(urldecode(http_build_query($conf).'3NNuFCd4gmY93c6G'.date('Y-m-d')));
        $url = "http://padapi.youxinpai.com/sms/send/";
        $conf['sn'] = $sn;
        echo "start send:".date('Y-m-d H:i:s').PHP_EOL;
        $this->load->library('ycurl');
        $this->ycurl->post($url, $conf);
        echo "end send:".date('Y-m-d H:i:s').PHP_EOL;
    }
    
	private function _send_sms($publish) {
		//取所有买家经销商
		$sql = "select d.*,u.mobile as user_mobile from user u
		left join dealer d on d.user_id=u.userid
        where u.mobile != ''";
		$dealer = $this->db->query($sql)->result_array();
		if(!$publish['publish_id'] || !$dealer) {
			return ;
		}
		$time = time();
		$insert = array();
		foreach($dealer as $d) {
			$insert[] = array(
				'publish_id' => $publish['publish_id'],
				'dealer_id' => intval($d['dealerid']),
				'mobile' => $d['user_mobile'],
				'sms' => "[捷豹路虎轿车内网拍]新发布拍品[{$publish['car_name']}]，起拍价{$publish['start_price']}万，竞拍结束时间".date('Y-m-d H:i', $publish['end_time']).'，请您关注。[优信拍]',
				'createtime'  => $time,
			);
		}
		if(count($insert)){
			$this->db->insert_batch('sms', $insert);
		}
	}

	//修改拍拍品时间
	public function alter_time() {
		$get  = $this->input->get();
		$post = $this->input->post();
		$pid  = $get['pid'];

		if($post['opt']==1){
			$start_time = strtotime($post['start_time']);
			$end_time = strtotime($post['end_time']);
			$pid = $post['pid'];
			$ret = $this->publish_model->update(array("start_time"=>$start_time,"end_time"=>$end_time),array('publishid'=>$pid));
			if($ret) {
				header("Content-Type: text/html; charset=UTF-8");
				echo "修改成功";
			} else {
				header("Content-Type: text/html; charset=UTF-8");
				echo "修改失败";
			}
			exit;
		}
		$publish = $this->publish_model->get_one($pid);
		$date = date('Y-m-d H:i:s', time());
		$this->smarty->assign('publish',$publish);
		$this->smarty->assign('date',$date);
		$this->smarty->display('admin/alter_time.html');
	}
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
