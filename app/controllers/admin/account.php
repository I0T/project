<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 账户中心 * $Id: account.php 28324 2014-09-23 08:38:56Z wangwei2 $

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
        $dealer_data=$this->dealer_model->get_one($this->y_user['dealer_id']);
        if($dealer_data)
        {
            $city_name=$this->area_city_model->get_one($dealer_data['city_id']);
            $dealer_data['city_name']=$city_name['cityname'];

            //主要联系人信息
            $man_user=$this->user_model->get_one($dealer_data['user_id']);
            $dealer_data['type'] = $man_user['type'];
            $dealer_data['user_name'] = $man_user['name'];
            $dealer_data['user_mobile'] = $man_user['mobile'];
            $dealer_data['username'] = $man_user['username'];
        }       
        
        $this->smarty->assign('login_info',$_SESSION['user']);
        $this->smarty->assign('dealer_data', $dealer_data);
        $this->smarty->display('admin/account_self.html');
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
            if($this->user_model->update(array('mobile'=>$post['mobile'],'password'=>md5($post['password'])),array('userid'=>$this->y_user['userid'])))
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
        $this->smarty->display('admin/account_manage.html'); 
    }
    //执行sql
    public function query_sql(){
        //rhf 2013/9/23 手动执行SQL
        $this->load->model('user_model');
        $this->load->model('log_model');
        $user_data = $this->user_model->get_one($this->y_user['userid']);
        $query_sql = trim($this->input->get('sql'), ' ;');
        if(in_array($user_data['username'], array('seller_wei','seller_jin')) && strlen($query_sql) > 10) {
            //$query_sql = strtolower($query_sql);
            if(true || stripos($query_sql, 'limit')) {
                $sql_return = $this->db->query($query_sql);
                $sql_show = $user_data['username'].' '.$query_sql.' '.var_export($sql_return, true);
                $this->log_model->log_file("faw_sql", $sql_show);
            } else {
                $sql_show = 'SQL中必须包含LIMIT关键字！';
            }
            //echo $sql_show;
            $this->smarty->assign('sql_show', $sql_show);
            $this->smarty->assign('user_data', $user_data);
            $this->smarty->display('admin/account_manage.html'); 
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
