<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//登录首页 * $Id: index.php 24961 2014-07-17 08:45:54Z jinlong $

class Index extends YXP_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	//登录首页
	public function index($page=1)
	{
		$this->user_model->login_redirect();
		$this->smarty->display('index.html');
	}

	//车辆详情页
	public function car_info()
	{
		$get = $this->input->get();
		$this->load->model(array('car_model','user_model','area_model'));
		$this->load->config('dict');
		$dict_car_type = $this->config->item('dict_car_type');
		if(!$this->user_model->is_login()) {
			$this->config->set_item('url_suffix', '');
			redirect('/');
		}
		$car = $this->car_model->get_one(intval($get['car_id']));
		if(!$car) {
			show_error('对不起，读取车辆信息失败', 200, '操作失败');
		}
		$car['car_type'] = $dict_car_type[$car['car_type']];
		$city = $this->area_model->get_city(array($car['city_id']));

		$car['city_name'] = $city[$car['city_id']];
        $this->smarty->assign('dict_domain_chake', $this->config->item('dict_domain_chake'));
		$this->smarty->assign('car', $car);
		$this->smarty->display('car_info.html');

	}
}
