<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//拍卖执行人 发拍管理 * $Id: auctionlist.php 26313 2014-08-14 06:19:38Z jinlong $

class Auctionlist extends YXP_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url','array'));
		$this->load->model(array('publish_model','publish_bid_model','dealer_user_model'));
	}

	//卖家交易管理
	public function index()
	{
		$this->publish_model->bidding_status();
		$get = $this->input->get();

		$ford = array();
        if ($get['brand_id'] && $get['series_id'])
        {
            try {
                $params = array(
                    array('type' => 'brand', 'key'  => 'iautos_brand_id',  'value' => $get['brand_id']),
                    array('type' => 'series','key'  => 'iautos_series_id', 'value' => $get['series_id']),
                    array('type' => 'mode',  'key'  => 'iautos_series_id', 'value' => $get['series_id']),
                );
                $result = $this->publish_model->get_car_type($params);
                unset($params);
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
                    'brand_id'    => $get['brand_id'],
                    'series_id'   => $get['series_id'],
                    'trim_id'     => $get['trim_id'],
                );
                
                $this->smarty->assign('ford', $ford);
                unset($ford);
            } 
            catch (Exception $e)
            {
                
            }
        }

		$get['status'] 	= $get['status'] ? intval($get['status']) : 1;
		$per_page 		= 10;
		$page 			= max(1, $get['page']);
		$limit 			= array(($page-1)*$per_page, $per_page);
		if($get['type']=='export')
    	{
    		//导出excel
           	$get['dealer_id'] = $_SESSION['user']['dealer_id'];
            $get['type'] 	  = $_SESSION['user']['type'];
			ini_set('memory_limit', '1024M');
			$publish = $this->publish_model->search($get, '', 'data');
			$this->exportexcel(1,$publish, 'publish.xls');
			
    	}

    	$get['dealer_id'] = $_SESSION['user']['dealer_id'];
    	$get['type'] 	  = $_SESSION['user']['type'];
		$publish 		  = $this->publish_model->search($get, $limit, 'data');
		
		//分页
		$this->load->config('page', true);
		$config = $this->config->item('page');
		$config['page_query_string'] = TRUE;
		$total_rows = $this->publish_model->search($get, '', 'count');
		$config['total_rows'] = !is_array($total_rows) && $total_rows > 0 ? $total_rows : 0;
		$config['per_page']   = $per_page;
		unset($get['page']);
		$config['base_url']   = '?'.http_build_query($get);
		$this->pagination->initialize($config);
		$links = $this->pagination->create_links();
		$this->smarty->assign('links', $links);
		$this->smarty->assign('publish', $publish);
		$this->smarty->assign('dict_domain_chake', $this->config->item('dict_domain_chake'));
		$this->smarty->assign('get', $get);
        $this->smarty->assign('login_info',$_SESSION['user']);
		$this->smarty->display("admin/auctionlist.html");
	}

	//出价记录
	function publish_bid() {
		$get = $this->input->get();
		$bid = $this->publish_bid_model->record($get['pid']);
		if($get['type']=='export')
    	{
    		//导出excel
			ini_set('memory_limit', '1024M');
			$bid = $this->publish_bid_model->record($get['pid']);
			$this->exportexcel(2,$bid, 'bid.xls');
    	}
		$this->smarty->assign('bid', $bid);
		$this->smarty->assign('pid', $get['pid']);
		$this->smarty->display('admin/publish_bid.html');
	}

	/**
     * 导出
     * @param  int     标识
     * @param  array   数据 
     * @param  string  文件名
     * @return void
     */
	private function exportexcel($sign, $data, $filename){
		$exceldata = array();
		if($sign == 1)
		{	
			$get = $this->input->get();
			//取成交
			$deal_order = $deal_dealer = array();
			if ($get['status'] == 3) {
				$highest_bid = array_muliti_field($data, 'publish_bid');
				$deal_order  = $this->publish_bid_model->get_all(array('bid'=>$highest_bid));
				$deal_order  = array_set_key($deal_order,'bid');
				$delaer_uid  = array_muliti_field($deal_order, 'uid');
				$deal_dealer = $this->dealer_user_model->get_all(array('userid'=>$delaer_uid));
				$deal_dealer = array_set_key($deal_dealer,'userid');
			}
			$i=1;
			foreach ($data as $k=>$d)
			{
				$exceldata[$k]['car_name'] = $d['car_name'];
				//$exceldata[$k]['prov_name'] = $d['prov_name'].'-'.$d['city_name'];
				$exceldata[$k]['no'] = $d['no'];
				$exceldata[$k]['vin'] = $d['vin'];
				$exceldata[$k]['pn'] = $d['pn'];
				$exceldata[$k]['createtime'] = $d['createtime'];
				$exceldata[$k]['dealername'] = $d['dealer_name'];
				$exceldata[$k]['code'] = $d['code'];
				$exceldata[$k]['end_time'] = $d['end_time'];
				$exceldata[$k]['start_time'] = $d['start_time'];
				$exceldata[$k]['bid_time'] = $d['bid_time'];
				$exceldata[$k]['order'] = $d['order'];
				$exceldata[$k]['top_price'] = $d['top_price'];
				$exceldata[$k]['pre_bid_price'] = $d['pre_bid_price'];
				$exceldata[$k]['price'] = $d['price'];
				$exceldata[$k]['status'] = $d['status'];
				$exceldata[$k]['buyer_dealtime'] = date('Y-m-d H:i:s',$deal_order[$d['publish_bid']]['createtime']);
				$exceldata[$k]['buyer_dealername'] = $deal_dealer[$deal_order[$d['publish_bid']]['uid']]['name'];
				$exceldata[$k]['total_price']=$deal_order[$d['publish_bid']]['total_price'];
				$exceldata[$k]['id'] = $i;
				$i++;
			}
			
				$this->smarty->assign('data', $exceldata);
				$this->smarty->assign('get', $get);
				$exceldata = $this->smarty->fetch('admin/export.html');
			
		}
		else
		{
			$i=1;
			foreach ($data as $k=>$d)
			{
				$exceldata[$k]['car_name']  = $d['car_name'];
				//$exceldata[$k]['area_name'] = $d['prov_name'].'-'.$d['city_name'];
				$exceldata[$k]['no']  = $d['no'];
				$exceldata[$k]['vin'] = $d['vin'];
				$exceldata[$k]['pn']  = $d['pn'];
				$exceldata[$k]['dealer_name'] = $d['dealer_name'];
				$exceldata[$k]['pstart_time'] = $d['pstart_time'];
				$exceldata[$k]['pcreatetime'] = $d['pcreatetime'];
				$exceldata[$k]['bcreatetime'] = $d['bcreatetime'];
				$exceldata[$k]['price'] = $d['price'];
				$exceldata[$k]['id']    = $i;
				$exceldata[$k]['type']  = $d['type']==1 ? '投标' : '竞价';
				$i++;
			}

			$this->smarty->assign('data', $exceldata);
			$exceldata = $this->smarty->fetch('admin/cj_history.html');     
		}
		$this->load->helper('download');
		force_download($filename, $exceldata);
		exit;
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
