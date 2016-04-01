<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 账户中心 * $Id: account.php 25854 2014-08-06 10:15:54Z jinlong $

class account extends YXP_Controller {

	function __construct() {
		parent::__construct();
		if(!$this->user_model->is_login()) {
			//redirect('/cp/login/index/');
		}
	}

    //公司基本信息
    public function index()
    {
        $this->load->model(array('dealer_model','area_city_model','user_model'));
        $dealer_data = $this->dealer_model->get_one($this->y_user['dealer_id']);
        if($dealer_data)
        {
            //$city_name=$this->area_city_model->get_one($dealer_data['city_id']);
            $city_name = $this->area_city_model->address($dealer_data['city_id']);
            $dealer_data['city_name'] = $city_name;

            //主要联系人信息
            $man_user=$this->user_model->get_one($dealer_data['user_id']);
            $dealer_data['dealer_name'] = $dealer_data['name'];
            $dealer_data['type'] = $man_user['type'];
            $dealer_data['name'] = $man_user['name'];
            $dealer_data['user_mobile']= $man_user['mobile'];
            $dealer_data['username'] = $man_user['username'];
        }
        $this->smarty->assign('dealer_data', $dealer_data);
        $this->smarty->display('dealer/account.html');
    }
    //账户管理
    public function manage()
    {
        $post = $this->input->post();
        $this->load->model('user_model');
        $user_data = $this->user_model->get_one($this->y_user['userid']);

        if($post)
        {

            if($user_data['password'] != md5($post['oldpwd'])){

                echo 2;
                exit;

            }
            if($this->user_model->update(array('mobile'=>$post['mobile'], 'password'=>md5($post['password']), 'updatetime' =>time()),array('userid'=>$this->y_user['userid'])))
            {
                echo 1;
                exit;
            }
            else
            {
                echo 0;
                exit;
            }
        }

     
        $this->smarty->assign('user_data', $user_data);
        $this->smarty->display('dealer/account_manage.html'); 
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
