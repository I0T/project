<?PHP

/*
 * 城市区域模型
 * $Id: area_model.php 24264 2014-07-08 07:15:59Z raohongfu $
 */

class Area_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->driver('cache');
		$this->load->helper(array('array'));
		$this->cache_ttl = 600;
	}

	/*
	 * 根据大区id获取大区信息
	 * $bigareaid 大区id数组;为空取全部;
	 * 返回数组，array(bigareaid => 名称)，...
	*/
	public function get_bigarea($bigareaid=array())
	{
		$key = 'area_bigarea_'.implode('-', $bigareaid);
		$result = $this->cache->memcache->get($key);
		if (!$result)
		{
			if ($bigareaid)
			{
				$this->db->where_in('bigareaid', $bigareaid);
			}
			$bigarea = $this->db->get('area_bigarea')->result_array();
			$result = array();
			foreach ($bigarea as $v)
			{
				$result[$v['bigareaid']] = $v['bigareaname'];
			}
			$this->cache->memcache->save($key, $result, $this->cache_ttl);
		}
		return $result;
	}

	/*
	 * 根据城市id获取城市信息
	 * $cityid 城市id数组，为空取全部
	 * 返回数组，array(cityid => cityname)，...
	*/
	public function get_city($cityid=array())
	{
		$key = 'area_city_'.implode('-', $cityid);
		$result = $this->cache->memcache->get($key);
		if (!$result)
		{
			if ($cityid)
			{
				$this->db->order_by('sort');
				$this->db->where_in('cityid', $cityid);
			}
			$city = $this->db->get('area_city')->result_array();
			$result = array();
			foreach ($city as $v)
			{
				$result[$v['cityid']] = $v['cityname'];
			}
			$this->cache->memcache->save($key, $result, $this->cache_ttl);
		}
		return $result;
	}

	/*
	 * 根据城市id获取城市信息
	 * $cityid
	 * 返回数组，array(cityid => 城市id， cityname=>城市名称 )，...
	*/
	public function get_city_by_id($cityid)
	{
		$key = 'area_city_id_'.$cityid;
		$result = $this->cache->memcache->get($key);
		if (!$result)
		{
			$this->db->where('cityid = '.intval($cityid));
			$result = $this->db->get('area_city')->row_array();
			$this->cache->memcache->save($key, $result, $this->cache_ttl);
		}
		return $result;
	}


	/*
	 * 根据城市名称获取城市信息
	 * $cityname 城市名称
	 * 返回数组，array(cityid => id，cityname=>名称)，...
	*/
	public function get_city_by_name($cityname)
	{
		$key = 'area_city_name_'.$cityname;
		$result = $this->cache->memcache->get($key);
		if (!$result)
		{
			$this->db->where('cityname', $cityname);
			$result = $this->db->get('area_city')->row_array();
			$this->cache->memcache->save($key, $result, $this->cache_ttl);
		}
		return $result;
	}

	/*
	 * 根据省份id获取省份信息
	 * $provinceid 省份id数组，为空取全部
	 * 返回数组，array( provinceid => 名称)，...
	*/
	public function get_province($provinceid=array())
	{
		$key = 'area_province_'.implode('-', $provinceid);
		$result = $this->cache->memcache->get($key);
		if (!$result)
		{
			if ($provinceid)
			{
				$this->db->order_by('sort');
				$this->db->where_in('provinceid', $provinceid);
			}
			$province = $this->db->get('area_province')->result_array();
			$result = array();
			foreach ($province as $v)
			{
				$result[$v['provinceid']] = $v['provincename'];
			}
			$this->cache->memcache->save($key, $result, $this->cache_ttl);
		}
		return $result;
	}

	/*
	 * 根据城市id获取大区省份信息
	 * $cityid 城市id
	 * 返回数组，array(bigareaid=>'',bigareaname=>'', provinceid=>'',provincename='',cityid=>'',cityname='')
	*/
	public function get_bigarea_province_by_city($cityid)
	{
		$key = 'area_bigarea_province_city_'.$cityid;
		$result = $this->cache->memcache->get($key);
		if (!$result)
		{	
			$this->db->select('b.bigareaid,b.bigareaname,p.provinceid,p.provincename,c.cityid,c.cityname');
			$this->db->join('area_province p', 'c.provinceid=p.provinceid', 'left');
			$this->db->join('area_bigarea b', 'c.bigareaid=b.bigareaid', 'left');
			if(is_array($cityid))
			{
				$this->db->where_in('c.cityid', $cityid);
				$result = $this->db->get('area_city c')->result_array();
				$result = array_set_key($result, 'cityid');

			}
			else
			{
				$this->db->where('c.cityid', $cityid);
				$result = $this->db->get('area_city c')->row_array();
			}

			$this->cache->memcache->save($key, $result, $this->cache_ttl);
		}
		return $result;
	}


	/*
	 * 根据大区id获取大区下省份信息
	 * $bigareaid 大区id
	 * 返回数组，array(provinceid => 名称)，...
	*/
	public function get_province_by_bigarea($bigareaid)
	{
		$key = 'area_bigarea_province_'.$bigareaid;
		$result = $this->cache->memcache->get($key);
		if (!$result)
		{
			$this->db->order_by('sort');
			$this->db->where('bigareaid', $bigareaid);
			$province = $this->db->get('area_province')->result_array();
			$result = array();
			foreach ($province as $v)
			{
				$result[$v['provinceid']] = $v['provincename'];
			}
			$this->cache->memcache->save($key, $result, $this->cache_ttl);
		}
		return $result;
	}

	/*
	 * 根据省份id获取省份下城市信息
	 * $provinceid 省份id
	 * 返回数组，array(cityid => 名称)，...
	*/
	public function get_city_by_province($provinceid)
	{
		$key = 'area_province_city_'.$provinceid;
		$result = $this->cache->memcache->get($key);
		if (!$result)
		{
			$this->db->order_by('sort');
			$this->db->where('provinceid', $provinceid);
			$city = $this->db->get('area_city')->result_array();
			$result = array();
			foreach ($city as $v)
			{
				$result[$v['cityid']] = $v['cityname'];
			}
			$this->cache->memcache->save($key, $result, $this->cache_ttl);
		}
		return $result;
	}

	/*
	 * 根据大区id获取大区下城市信息
	 * $bigareaid 大区id
	 * 返回数组，array(cityid => 名称)，...
	*/
	public function get_city_by_bigarea($bigareaid)
	{
		$key = 'area_bigarea_city_'.$bigareaid;
		$result = $this->cache->memcache->get($key);
		if (!$result)
		{
			$this->db->order_by('sort');
			$this->db->where('bigareaid', $bigareaid);
			$city = $this->db->get('area_city')->result_array();
			$result = array();
			foreach ($city as $v)
			{
				$result[$v['cityid']] = $v['cityname'];
			}
			$this->cache->memcache->save($key, $result, $this->cache_ttl);
		}
		return $result;
	}

}
