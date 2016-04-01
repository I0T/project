<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 买家经销商 首页 * $Id: index.php 25814 2014-08-06 07:49:51Z jinlong $

class Index extends YXP_Controller {

	function __construct() {
		parent::__construct();
		/*if(!$this->user_model->is_login()) {
			//redirect('/cp/login/index/');
		}*/
	}

    //买家首页
    public function index()
    {
        $get=$this->input->get();
        $this->load->model(array('dealer_model','message_model','user_model'));
        $dealer_data=$this->dealer_model->get_one($this->y_user['dealer_id']);
        if($dealer_data)
        {
            //主要联系人信息
            $man_user=$this->user_model->get_one($dealer_data['user_id']);
            $dealer_data['user_name']=$man_user['name'];
            $dealer_data['user_mobile']=$man_user['mobile'];
            $dealer_data['role'] = $man_user['type']==2?"广汽菲克买家":"经纪公司";
        }
        //消息
		$message=$this->message_model->get_all(array(),'createtime','desc');
        $this->smarty->assign('get', $get);
		$this->smarty->assign('message', $message);
		$this->smarty->assign('dealer_data', $dealer_data);
		$this->smarty->display('dealer/index.html');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
