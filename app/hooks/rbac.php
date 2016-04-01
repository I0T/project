<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rbac
{
    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->config('rbac');
        $this->CI->load->model('user_model');
        $this->CI->load->helper('url');

		$this->needlogindir = array('admin','cp','dealer');
        //ipad、winform需要进入下一层目录
        $this->url_dir = $this->CI->uri->segment(1);
        if (in_array($this->url_dir, $this->needlogindir))
        {
            $this->url_model = $this->CI->uri->segment(2);
            $this->url_method = $this->CI->uri->segment(3);
        }
        else
        {
            $this->url_model = $this->CI->uri->segment(1);
            $this->url_method = $this->CI->uri->segment(2);
        }

    }

    //操作权限认证
    function auth()
    {
		$this->CI->config->set_item('url_suffix', '');
        $notneedlogin = $this->CI->config->item('notneedlogin');
        if (!in_array($this->url_dir, $this->needlogindir) && in_array($this->url_model, $notneedlogin)) {
			return true;
		} else {
            if ($this->CI->user_model->is_login()) {
				$user = $this->CI->user_model->login_info();
				if($user['type'] == 3 && $this->url_dir != 'dealer') {
					redirect('/dealer/');
				} elseif ($user['type'] == 2 && $this->url_dir != 'dealer') {
					redirect('/dealer/');
				} elseif ($user['type'] == 1 && $this->url_dir != 'admin') {
                    redirect('/admin/');
                } elseif ($user['type'] == 0 && $this->url_dir != 'admin') {
                    redirect('/admin/');
                }
			} else {
				redirect('/error/');
			}
        }
    }

    function auth_error()
    {
       // show_error('你没有权限进行此操作', 403, '提示信息');
    }

    //系统日志记录
    function log()
    {
        //哪些操作需要记录日志
        $rbac_log_function = $this->CI->config->item('rbac_log_function');
        if (in_array($this->url_method, $rbac_log_function) && ($this->CI->input->get() || $this->CI->input->post()))
        {
            $actionid = 0;
            $this->CI->load->model('action_model');
            $action = $this->CI->action_model->get_action_by_classid_functionid($this->url_model, $this->url_method);
            if ($action)
            {
                $actionid = $action['actionid'];
            }
            $user = $this->CI->user_model->login_info();
            if (!$user)
            {
	            $user['mastername'] = '-';
            }
            $this->CI->load->model('log_model');
            $this->CI->log_model->record($user['mastername'], $actionid);
        }
    }
}
?>