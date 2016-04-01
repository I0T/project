<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// * $Id: area.php 24264 2014-07-08 07:15:59Z raohongfu $

class Area extends YXP_Controller {

    public function __construct()
    {
		parent::__construct();
		$this->load->model('area_model');
		$this->load->driver('cache');
    }

    /*
    * 获取全部大区、省份城市列表
    *
    * 返回 ...
    */
    public function allcity()
    {
		$key = 'area_allcity';
		$result = $this->cache->memcache->get($key);
		if (!$result)
		{
			$result = array();
			$bigarea = $this->area_model->get_bigarea();
			foreach ($bigarea as $kba => $vba)
			{
			$a = array('id'=>$kba, 'name'=>$vba);
			$prov = $this->area_model->get_province_by_bigarea($kba);
			foreach ($prov as $kp=>$vp)
			{
				$p = array('id'=>$kp, 'name'=>$vp);
				$city = $this->area_model->get_city_by_province($kp);
				foreach ($city as $kc=>$vc)
				{
				$p['list'][] = array('id'=>$kc, 'name'=>$vc);
				}
				$a['list'][] = $p;
			}
			$result[] = $a;
			}
			$this->cache->memcache->save($key, $result, 600);
		}
		
		echo json_encode($result);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */