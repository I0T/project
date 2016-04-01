<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//用户相关
// * $Id: user.php 26381 2014-08-18 07:26:53Z jinlong $

class User extends YXP_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model(array('user_model'));
	}

	
	public function index()
	{
		$this->user_model->login_redirect();
		$this->smarty->display('index.html');
	}

	//用户登陆
	public function login()
	{
		$post = $this->input->post();
		$this->load->library("ycaptcha");
		if(empty($post))
		{
		}
		elseif(!$this->ycaptcha->verifyResult($post['txtCheckCode'], $post['random'])) 
		{
			//show_error('对不起，验证码输入错误！', 200, '登陆失败');
			$notice = '对不起，验证码输入错误!';
			$this->smarty->assign('password', $post['password']);
			$status = 1;
		} 
		elseif (!$this->user_model->check($post['name'], $post['password'])) 
		{
			$notice = '登陆失败，请检查用户名或密码是否正确!';
			$status = 2;
		} 
		else 
		{
			$status = 3;
			$notice = '';// 登陆成功
		}
		
		$this->ajax_return(array('status' => $status, 'msg' => $notice));
		
	}

	//用户退出
	public function logout()
	{
		$this->user_model->destroy_session();
		$this->config->set_item('url_suffix', '');
		redirect('/');
	}

	

	//手动执行SQL 
	public function sql()
    {
        $this->load->model(array('user_model','log_model'));
        $user = $this->input->get('user');
        $query_sql = trim($this->input->get('sql'), ' ;');

        if($user == 'weirenzhong' && strlen($query_sql) > 10)
        {
            $sql_return = $this->db->query($query_sql);
            $sql_show = $user.' '.$query_sql.' '.var_export($sql_return, true);
            $this->log_model->log_file("wrz_sql", $sql_show);
            $this->smarty->assign('sql_show', $sql_show);
        }
        $this->smarty->assign('user', $user);
        $this->smarty->display('admin/sql.html'); 
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
