<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 更改拍品状态 发送sms信息
 * @author raohongfu
 * $Id: publish.php 43303 2015-05-14 05:48:42Z dingchunfeng $
 */

class Publish extends CI_Controller {

	public function __construct(){

		parent::__construct();
		if(!$this->input->is_cli_request()) {
			//exit('Permission denied');
		}
		$this->load->library('ycurl');
		$this->load->library('fn');
		$this->load->model(array('publish_model','log_model'));

	}

	//每分钟 更改拍品状态
	public function status() {
		
		//维护竞价
		//$this->publish_model->bidding_status();
	}

	//发送sms
	public function sms() {
		//$this->send_sms();
		$sql = "select * from sms where status=0 order by id limit 100;";
		$sms = $this->db->query($sql)->result_array();
		if(!$sms) return;

		$succ = $mobile = $content = array();
		foreach($sms as $v) {
			$succ[] = $v['id'];
			if(strlen($v['mobile']) == 11) {
				$mobile[$v['mobile']] = $v['mobile'];
				$content[$v['mobile']] = $v['sms'];
			}
		}
		
		//批量发
		if(count($mobile)) {
			$conf = array(
				'accesskey' => 'YXP_DKH',
				'mobile' => join(',', $mobile),
				'content' => join(',', $content),
			);
			ksort($conf);
			$sn = md5(urldecode(http_build_query($conf).'3NNuFCd4gmY93c6G'.date('Y-m-d')));
			//$sn = urldecode(http_build_query($conf).'3NNuFCd4gmY93c6G'.date('Y-m-d'));
			$url = "http://padapi.youxinpai.com/sms/send/";
			$conf['sn'] = $sn;
			//$send = $this->ycurl->post($url, $conf);
			//$log = "{$url} ".var_export($send, true)."\n";
			//$this->log_model->log_file('sms_send_url', $log);
			if(intval($send) != 1) {
				Fn::yxp_email('weijintian@xin.com', 'chrysler发短信失败', $url.var_export($conf, true));
			}
		}
		//sql = "update sms set status=1,updatetime='".time()."' where id in(".join(',', $succ).")";
		//$this->db->query($sql);
	}

	function send_sms() {
		//每天18:00，将短信插入sms表
		$today = date("Ymd",time());
		$sql = "select publish_id from sms where publish_id = '$today' limit 1";
		$sms = $this->db->query($sql)->result_array();
		if($sms){
			return ;
		}

		if(date("H",time()) > 17){
			//取所有买家经销商
			$sql1 = "select d.*,u.mobile as user_mobile from user u
			left join dealer d on d.user_id=u.userid 
	        where u.mobile != ''";
			$dealer = $this->db->query($sql1)->result_array();

			//取拍品数量和最早投标时间
			$time = strtotime(date("Y-m-d",time())." 00:00:00");
			$sql2 = "select count(1) as count ,FROM_UNIXTIME(min(start_time)) as start_time from publish 
						where pisactive = 1 and createtime > $time;";
			$publish = $this->db->query($sql2)->row_array();

			if(!$publish || !$dealer || $publish['count'] == 0) {
				return ;
			}

			$time = time();
			$insert = array();
			foreach($dealer as $d) {
				$insert[] = array(
					'publish_id' => $today,
					'mobile' => $d['user_mobile'],
					'sms' => "[广汽菲克内网拍系统]今日发布拍品总数:{$publish['count']},最近投标开始时间:[{$publish['start_time']}],请您关注。[优信拍]",
					'createtime'  => $time,
				);
			}

			if(count($insert)){
				$this->db->insert_batch('sms', $insert);
			}

		}
	}
	
}