<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// * $Id: api.php 26363 2014-08-18 04:07:24Z jinlong $

class Api extends YXP_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('area_model');
	}

	// 车辆信息 pdf 上传
	public function upload()
	{
		if(!$_FILES['Filedata']['tmp_name']) {
			exit;
		}
		$file = rtrim($_SERVER['SITE_CACHE_DIR'],'/').'/'.md5($_FILES['Filedata']['tmp_name']).'.pdf';
		move_uploaded_file($_FILES['Filedata']['tmp_name'], $file);
		$data = array(
			'app' => 'customer',
			'key' => 'i9238420j234j23r',
			'method' => 'buf',
			'file' => file_get_contents($file),//二进制数据
		);
		$this->load->library('ycurl');
		$up_res = $this->ycurl->post("http://img3.youxinpai.com/upload_file.php", $data);
		echo $up_res;
		//echo '{"code":1,"file":"\/g1\/M00\/12\/82\/rBBkn1MC9NOAaRjeAAAAAXzc77c2482158","size":1}';
	}

	//获取省份列表
	function getpros()
	{
		$result = $this->area_model->get_province();
		echo json_encode($result);exit;
	}

	//获取城市列表
	function getcity()
	{
		$post = $this->input->post();
		$result = $this->area_model->get_city_by_province(intval($post['checkval']));
		echo json_encode($result);exit;
	}

	//查看拍品发拍场次是否已满
	function checktime()
	{
		$get = $this->input->get();
		$end_time  = urldecode($get['end_time']);
		$date_time = strtotime($end_time);
		$bid_time  = date('YmdH', $date_time);
		$count = $this->db->get_where('publish', array('bid_time' => $bid_time, 'pisactive' => 1, 'status' => 1))->num_rows();
		if($count >= 20 ){
			$result = 0 ;
		}else{
			$result = 1;
		}
		echo $result;exit;
	}

  	//验证账户是否已经存在
	function checkuser()
	{
		$get = $this->input->get();
		$this->load->model(array('user_model','dealer_model'));
		$field = $get['fieldId'];
		$dealerid = $get['dealerid'];

		switch ($field) {
			case 'dealererp':
				$dealererp = urldecode($get['fieldValue']);
				$arr = array("name"=>$dealererp);
				if($dealerid){
					$arr['dealer_id_isnot'] = $dealerid;
				}
				$dealer = $this->user_model->get_row($arr);
				if($dealer){
					$result = 0;
				}else{
					$result = 1;
				}
			break;
			case 'dealercode':
				$dealercode = urldecode($get['fieldValue']);
				$arr = array("username"=>$dealercode);
				if($dealerid){
					$arr['dealer_id_isnot'] = $dealerid;
				}
				$dealer = $this->user_model->get_row($arr);
				if($dealer){
					$result = 0;
				}else{
					$result = 1;
				}
			break;
			case 'no':
				$no = urldecode($get['fieldValue']);
				$arr = array("no"=>$no);
				if($dealerid){
					$arr['dealer_id_isnot'] = $dealerid;
				}
				$user = $this->user_model->get_row($arr);
				if($user){
					$result = 0;
				}else{
					$result = 1;
				}
			break;
			case 'mobile':
				$mobile = urldecode($get['fieldValue']);
				$arr = array("name"=>$mobile);
				if($dealerid){
					$arr['dealer_id_isnot'] = $dealerid;
				}
				$user = $this->user_model->get_row($arr);
				if($user){
					$result = 0;
				}else{
					$result = 1;
				}
			break;
			
			default:
				# code...
			break;
		}		
		
		echo $result;exit;
	}
	/**
	 * ajax上传车辆图片
	 *
	 */
	public function ajax_upload_pic()
	{
		$files = $_FILES['files']['tmp_name'];

		if ($files) 
		{
			foreach ($files as $fkey => $fval) {
				$result = $this->_upload_images($fval);
			}

			if ($result['code'] == 1) 
			{
				$json['files'][] = array(
			    	'code'   => $result['code'],
			    	'pic'    => $result['pic'],
			    	'width'  => $result['width'],
			    	'height' => $result['height'],
			    	'idname' => 'id_'.mt_rand(10,100),
			    );
			}
			else 
			{
				$json['files'][] = array(
					'code' => $result['code'],
					'msg'  => $result['msg'],
				);
			}
		}
		else
		{
			$json['files'][] = array(
					'code' => -1,
					'msg'  => '参数错误',
			);
		}
		/*$json['files'][] = array(
	    	'code'   => 1,
	    	'pic'    => '/g1/M00/26/14/rBBkn1OpCdKAFaB1AAUZS7F2R2k648.jpg',
	    	'idname' => 'id_'.mt_rand(10,100),
	    );*/

		echo json_encode($json);exit;
	}
	/**
	 * 通用curl上传图片
	 * @param string $file 需上传的文件
	 * @return json
	 */
	private function _upload_images($file)
	{
		$this->load->library('ycurl');//加载curl
		$url = 'http://img3.youxinpai.com/upload.php';

		$fields = array(
				'app' => 'bx',
				'key' => 'NxzeJTrGH8BX',
				'pic' => '@'.$file,
			);

		$link = $this->ycurl->post($url, $fields);
		return json_decode($link, true);
	}

	//弹框查看通知详情
	public function view_message() 
	{
		$get = $this->input->get();
        if($get['messageid'])
        {
			$this->load->model('message_model');
            $message_info = $this->message_model->get_one($get['messageid']);
            $this->smarty->assign('message_info', $message_info);
            $this->smarty->display('api_view_message.html');
		}

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
