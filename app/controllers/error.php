<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//超时或者在别处登陆提醒
// * $Id: error.php 25606 2014-08-01 08:20:03Z jinlong $

class Error extends YXP_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	//用户登陆提示
	public function index()
	{
		$this->smarty->display('error.html');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
