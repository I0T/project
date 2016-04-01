<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 拍卖执行人 首页 * $Id: index.php 25583 2014-08-01 04:20:28Z jinlong $

class Index extends YXP_Controller {

	function __construct() {
		parent::__construct();
		
	}

    // 卖家首页
    public function index()
    {
        //消息
        $this->load->model(array('message_model','user_model'));
		$message = $this->message_model->get_all(array(),'createtime','desc');
		$this->smarty->assign('message', $message);
        $result = $this->user_model->get_one($this->y_user['userid'], $field = array('username','mobile','type','name'));
        if($result['type']==0){
            $result['role'] = "广汽菲克卖家";
        }else{
            $result['role'] = "广汽菲克买家";
        }
        $this->smarty->assign('result',$result);
        $this->smarty->assign('login_info',$_SESSION['user']);
        $this->smarty->display('admin/index.html');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
