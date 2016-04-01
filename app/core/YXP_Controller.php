<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//yxp 基类
class YXP_Controller extends CI_Controller {

	//当前 控制器名称 class
	public $y_c;

	//当前 方法名称 action
	public $y_a;

	//当前 登陆用户信息
	public $y_user;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('smarty','pagination'));

		//初始化控制器名称和方法名称
		$this->y_c = $this->uri->rsegments[1];
		$this->y_a = $this->uri->rsegments[2];

		//加当前登陆用户信息
		$this->load->model(array('publish_model','user_model'));
		$this->y_user = '';
		if($this->user_model->is_login()) {
			$this->y_user = $this->user_model->login_info();
			$this->smarty->assign('y_user', $this->y_user);
		}
		//初始化域名 便于测试
		if(isset($_SERVER["HTTP_HOST"]) && $_SERVER["HTTP_HOST"] == 'bx.youxinpai.com') {
			$this->padapi_url = 'http://padapi.youxinpai.com';
			$this->bx_url = 'http://bx.youxinpai.com';
			$this->chake_url = 'http://www.chake.net';
		} else {
			$this->padapi_url = 'http://padapi.test.youxinpai.com';
			$this->bx_url = 'http://'.$_SERVER["HTTP_HOST"];
			$this->chake_url = 'http://test.checkauto.com.cn';
		}
		$this->smarty->assign('y_c', $this->y_c);
		$this->smarty->assign('y_a', $this->y_a);
		//$this->output->enable_profiler(TRUE);

	}

	/**
	 *  返回分页列表序号
	 * @param:  int $page      页数
	 * @param:  int $per_page  每页个数
	 * @return: array
	 */
	public function listid($page,$per_page)
	{
		$pages=array();
		for ($i=($page-1)*$per_page; $i < $page*($per_page); $i++)
		{
			$pages[]= $i+1;
		}
		return $pages;
	}

	//接口最终输出
	public function y_view($data) {
		header('Content-type: application/json');
		echo json_encode($data);
		exit;
	}

	//格式化 json 数据
	public function y_json($str, $type='string') {
		$type = strtolower($type);
		if($type == 'int') {
			$str = intval($str);
		} else if($type == 'string') {
			if(!is_string($str)) {
				$str = "{$str}";
			}
		} else if($type == 'float') {
			$str = floatval($str);
		}
		return $str;
	}

	//加载某配置文件，并获取某项的值
	public function y_conf($file, $field) {
		$this->load->config($file);
		if(is_array($field)) {
			$resp = array();
			foreach($field as $f) {
				$resp[$f] = $this->config->item($f);
			}
			return $resp;
		}
		return $this->config->item($field);
	}

	//写日志文件
	public function y_log($file, $str) {
		$this->load->model('log_model');
		$this->log_model->log_file($file, $str, "{$this->y_c}/{$this->y_a}");
	}

	/**
	 *  汇率转换 from,to in ("USD","CNY")
	 *  @param $from_Currency: 需转换货币类型
	 *  @param $to_Currency:转换后的货币类型 现汇买入价
	 */
	public function get_exchange_rate($from_Currency='USD', $to_Currency='CNY') {
		$from_Currency = urlencode($from_Currency);
		$to_Currency = urlencode($to_Currency);
		$url = "http://download.finance.yahoo.com/d/quotes.html?s=".$from_Currency.$to_Currency."=X&f=sl1d1t1ba&e=.html";

		$mkey = md5($url);
		$this->load->driver('cache');
		$rate = $this->cache->memcache->get($mkey);
		if($rate) {
			return $rate;
		}

		$this->load->library("ycurl");
		$rawdata = $this->ycurl->get($url);
		$data = explode(',', $rawdata);
		$rate = floatval($data[1]) - 0.02;
		$this->cache->memcache->save($mkey, $rate, 300);
		return $rate;
	}

	//打印调试
	public function p($data)
	{
		echo '<pre>';
		print_r($data);
		echo '<pre>';
		exit;
	}

	//替换字符 把指包含的中文替换为俄文
	public function y_replace($label, $string) {
		$this->lang->load('russia', 'yxp');
		$ru = $this->lang->line('russia');
		$zh = $this->lang->line('zh');
		$s_arr = explode(',', str_replace(',', '/,/', '/'.$zh[$label].'/'));
		$r_arr = explode(',', $ru[$label]);
		return preg_replace($s_arr, $r_arr, $string);
	}

	/**
	 * ajax 请求数据返回
	 * @param: array
	 * @return:viod
	 */
	public function ajax_return($arr=array()) {
		echo json_encode($arr);
		exit;

	}

}
