<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 系统账户管理 发起拍卖 * $Id: manager.php 26058 2014-08-08 07:46:43Z jinlong $

class Manager extends YXP_Controller {

	public function __construct() {
		parent::__construct();
		$this->per_page = 10;
		$this->load->library("smarty", "session");
		$this->load->helper(array('form', 'url'));
		$this->load->model(array('user_model','message_model','dealer_model','area_model','dealer_user_model'));
	}
        
	//经销商管理列表
	public function index($page=1)
	{
		$get   = $this->input->get();
		$get   = $get ? $get : array();
		$page  = $this->input->get('page');
		$page  = max ( 1, $get ['page'] );
		$limit = array(($page-1)*$this->per_page, $this->per_page);
		$where = array();
		$where = $this->_build_inbox_where($get);
		$dealer_info = $this->dealer_model->get_all_dealer($where, $limit, 'data');
		$get_bigarea = $this->area_model->get_bigarea();
		if($dealer_info)
		{
			//分页
			$this->load->library(array('pagination'));
			$this->load->config('page', true);
			$config = $this->config->item('page');
			$config['page_query_string'] = TRUE;
			unset($get ['page']);
			$config['base_url']   = '?'.http_build_query($get);
			$config['total_rows'] = $this->dealer_model->get_all_dealer($where, '', 'count');
			$config['per_page']   = $this->per_page;
			$this->pagination->initialize($config);
			$links = $this->pagination->create_links();
			//添加序列号
			$pages= $this->listid($page,$this->per_page);
			foreach ($dealer_info as $key => $value)
			{
				$dealer_info[$key]['ids'] = $pages[$key];
			}
			foreach($dealer_info as $key=>$val){
				if($val['bigareaid'] == 0){
					$dealer_info[$key]['bigarea'] = '暂无大区';
				}else{
					$dealer_info[$key]['bigarea'] = $get_bigarea[$val['bigareaid']];
				}
				if($val['city_id'] == 0){
					$dealer_info[$key]['city'] = '暂无城市';
				}else{
					$city= $this->area_model->get_city(array($val['city_id']));
					$dealer_info[$key]['city'] = $city[$val['city_id']];
				}
			}
		}

		$this->smarty->assign('page', $page);
		$this->smarty->assign('get', $get);
		$this->smarty->assign('links', $links);
		$this->smarty->assign('dealer_info', $dealer_info);
		//$this->p($dealer_info);
		$this->smarty->display('admin/manager.html');
	}

	/**
	 * 账户管理
	 * @return json || void
	 */
	public function account()
	{
		$post = $this->input->post();
        if($post['opt'] == 'edit')
        {
        	if(md5($post['password']) != md5($post['pwd2']))
        	{
        		$json['ret'] = -1;
                $json['msg'] = '修改失败，两次输入的密码不一致';
        	}
        	else
        	{
	            $data = array(
	            	'mobile' => trim($post['mobile']),
	            	'password' => md5($post['password']),
	            	'updatetime' => time(),
	            );
	            $userid = array('userid' => $this->y_user['userid']);
	            $result = $this->user_model->update($data, $userid);
	            if($result) {
	                $json['ret'] = 1;
	                $json['msg'] = '修改成功';
	            }else {
	                $json['ret'] = -1;
	                $json['msg'] = '修改失败';
	            }
        	}
            $this->y_view($json);
        }
        else
        {
			$this->smarty->display('admin/account.html');
        }
        
	}

	//删除经销商
	function del()
	{
		$get  = $this->input->get();
		$page = $get['page'];
		$url  = '/admin/manager/index/?page=';
		if($get['opt'] == 'dealerid')
		{
			$dealer = $this->dealer_model->get_one(intval($get['id']));

			if($this->dealer_model->delete(intval($get['id'])))
			{		
				$page 	= max(1, intval($page));
				$limit	= array(($page-1)*$this->per_page, $this->per_page);
				$where  = array();
				$where  = $this->_build_inbox_where($get);
				$dealer_info = $this->dealer_model->get_all_dealer($where, $limit, 'count');
				if(!$dealer_info)
				{
					$page -= 1;
					$page  = max(1, $page);
				}

				$url .= $page;
				if($get['provinceid'])
				{
					$url .= '&provinceid='.$get['provinceid'];
				}
				if($get['cityid'])
				{
					$url .= '&cityid='.$get['cityid'];
				}
				if($get['dealername'])
				{
					$url .= '&dealername='.$get['dealername'];
				}
				if($this->dealer_user_model->delete(intval($dealer['user_id']))){
					redirect($url);
				}
			}
		}
		redirect('/admin/manager/index/?page='.$page);
	}

	/**
	 * 重置密码为123abc
	 *
	 * @param array $post 参数
	 *
	 * @return json
	 */
	public function reset_pwd()
	{
		$post   = $this->input->post();
		$userid = $post['userid'];
		$res 	= $this->user_model->update(array('password' => md5('123456')), array('userid' => $userid));
		if($res)
		{
			$msg = array('status' => 1, 'msg' => '重置密码成功');
		}
		else
		{
			$msg = array('status' => 0, 'msg' => '重置失败');
		}
		$this->ajax_return($msg);
	}

	/**
	 * 组装inbox where条件
	 * @param array $get get参数
	 * @return array
	 */
	private function _build_inbox_where($get)
	{
		$where = array();
		if($get['cityid']) 
		{
			$where['city_id'] = $get['cityid'];
		}

		if($get['provinceid']) 
		{
			$where['pro_id'] = $get['provinceid'];
		}

		if($get['bigareaid']) 
		{
			$where['bigareaid'] = $get['bigareaid'];
		}

		if($get['username'])
		{
			$where['user'.'.'.'name'] =  $get['username'];
		}

		if($get['dealername'])
		{
			$where['dealername'] =  $get['dealername'];
		}
		return $where;
	}

	
	//添加或修改买家帐号信息
	public function add_manager()
	{	
		$post = $this->input->post();
		$get = $this->input->get();
		//	保存
		if ($post['opt'])
		{
			$dealername = $post['dealername']?trim($post['dealername']):"";
			$dealercode = $post['dealercode']?trim($post['dealercode']):"";
			$pro_id 	= intval($post['provinceid']);
			$city_id 	= intval($post['cityid']);
			$name 		= trim($post['name']);
			$password   = md5(trim($post['password']));
			$mobile     = trim($post['mobile']);
			$type       = 2;

			//新增opt＝1,修改opt＝2
			if($post['opt']==1)
			{	
				$datas = array('password'=>$password,'mobile'=>$mobile,'name'=>$name,'createtime'=>time(),'username'=>$dealercode,'type'=>$type);
				$resultid=$this->dealer_user_model->save($datas);
				if($resultid)
				{
					$data = array('name'=>$dealername,'pro_id'=>$pro_id,'city_id'=>$city_id,'user_id'=>$resultid,'createtime'=>time());
					if($dealerid=$this->dealer_model->save($data))
					{
						echo $this->dealer_user_model->update(array('dealer_id'=>$dealerid),'userid ='.$resultid);exit;
					}
				}
			}
			elseif($post['opt']==2)
			{
				$data = array('name'=>$dealername, 'pro_id'=>$pro_id, 'city_id'=>$city_id, 'updatetime'=>time());
				$result= $this->dealer_model->update($data,'dealerid ='.$post['dealerid']);
				if($result)
				{
					$update_data = array('name'=>$name,'mobile'=>$mobile,'updatetime'=>time());
					echo $this->dealer_user_model->update($update_data,'userid ='.$post['user_id']);exit;
				}
			}
		}
		else
		{
			
			//编辑
			if($dealerid = intval($_GET['dealerid']))
			{
				$opt = 2 ;
				$dealer_info = $this->dealer_model->get_one($dealerid);
				$user_info   = $this->dealer_user_model->get_one($dealer_info['user_id']);
				$type 		 = $user_info['type'];
				$bigarea_provinceArr 		= $this->area_model->get_bigarea_province_by_city($dealer_info['city_id']);
				$dealer_info['provinceid'] 	= $bigarea_provinceArr['provinceid']?$bigarea_provinceArr['provinceid']:0;
				$dealer_info['bigareaid'] 	= $bigarea_provinceArr['bigareaid']?$bigarea_provinceArr['bigareaid']:0;
				$dealer_info['cityid'] 		= $bigarea_provinceArr['cityid']?$bigarea_provinceArr['cityid']:0;
				$this->smarty->assign('dealer_info', $dealer_info);
				$this->smarty->assign('user_info', $user_info);
			}
			else
			{//新增
				$opt = 1;
				$type = $get['type'];
			}
			$this->smarty->assign('get', $get);
			$this->smarty->assign('opt', $opt);
			$this->smarty->assign('type', $type);
			$this->smarty->assign('dealer', $dealer_info);
			$this->smarty->assign('user', $user_info);
			$this->smarty->display('admin/add_manager.html');
		}
	}

}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
