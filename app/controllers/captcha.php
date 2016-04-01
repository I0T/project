<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// * $Id: captcha.php 24264 2014-07-08 07:15:59Z raohongfu $

class Captcha extends YXP_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('area_model');
	}

	//验证码
	public function index()
	{
		$this->load->library("ycaptcha");

		/* 保存验证码的标识session_name */
		$SessName = $this->input->get('code');
		$this->ycaptcha->CreateImage($SessName);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
