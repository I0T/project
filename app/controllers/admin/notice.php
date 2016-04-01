<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 通知信息管理 发起拍卖 * $Id: notice.php 25868 2014-08-07 01:20:43Z jinlong $

class Notice extends YXP_Controller {

	public function __construct() {
		parent::__construct();
		$this->per_page = 10;
		//每页个数
		$this->load->library("smarty", "session");
		$this->load->helper(array('form', 'url'));
		$this->load->model(array('message_model'));
	}

	//通知信息列表
	public function index()
	{
		$get   = $this->input->get();
		$get   = $get ? $get : array();
		$page  = max ( 1, intval($get ['page']) );
		$limit = array(($page-1)*$this->per_page, $this->per_page);
		$order = array('createtime' => 'desc');
		$info  = $this->message_model->get_message_list('', $order,	$limit, 'data');
		//分页
		$this->load->config('page', true);
		$config = $this->config->item('page');
		$config['page_query_string'] = TRUE;
		$config['per_page'] = $this->per_page;
		unset($get['page']);
		$config['base_url'] = '?'.http_build_query($get);
		$config['total_rows'] = $this->message_model->get_message_list($where, '',$limit, 'num');
		$this->load->library('pagination');
		$this->pagination->initialize($config);
		$links = $this->pagination->create_links();
		//序列号
		$pages= $this->listid($page,$this->per_page);
		foreach ($info as $key => $value)
		{
			$info[$key]['ids'] = $pages[$key];
		}
		$this->smarty->assign('page', $page);
		$this->smarty->assign('info', $info);
		$this->smarty->assign('name', $this->y_user['name']);
		$this->smarty->assign('links', $links);
		$this->smarty->assign('total', $config['total_rows']);
		$this->smarty->display('admin/notice.html');
	}

	//$opt=1(添加)$opt=2(更新)$opt=3(查看)
	public function addnotice()
	{
		$opt = 1 ;
		if(!empty($_GET))
		{	
			if($messageid = intval($_GET['messageid']))
			{
				$opt = 2 ;
			}
			else if($messageid = intval($_GET['messageid_show']))
			{
				$opt = 3 ;
			}
			$info = $this->message_model->get_one($messageid);
			$this->smarty->assign('info', $info);
		}
		$this->smarty->assign('messageid', $_GET['messageid']?$_GET['messageid']:'');
		$this->smarty->assign('opt', $opt);
		$this->smarty->display('admin/addnotice.html');
	}

	//添加或者修改通告信息
	public function sends()
	{
		$post = $this->input->post();
		//opt=1(save) opt=2(update)
		if($post['opt'] == 1)
		{
			$data = array('title'=>trim($post['title']),'content'=>trim($post['content']),
			'createtime'=>time(),'updatetime'=>time(),'user_id'=>$this->y_user['userid']);
			echo $this->message_model->save($data);exit;
		}
		else
		{
			$data = array('title'=>trim($post['title']),'content'=>trim($post['content']),'updatetime'=>time(),'user_id'=>$this->y_user['userid']);
			echo $this->message_model->update($data,'messageid ='.$post['messageid']);exit;
		}
	}

	//删除通告信息
	function del()
	{	
		$get = $this->input->get();

		if($this->message_model->delete(intval($get['id'])))
		{	
			$page 	= max(1, intval($get['page']));
			$limit 	= array(($page-1)*$this->per_page, $this->per_page);
			$info 	= $this->message_model->get_message_list('', $order,	$limit, 'data');
			if(!$info)
			{	$page -= 1;
				$page  = max(1, $page);
			}
			redirect('/admin/notice/index/?page='.$page);
		}
		redirect('/admin/notice/index/?page='.$get['page']);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
