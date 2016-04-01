<?PHP

// * $Id: log_model.php 24264 2014-07-08 07:15:59Z raohongfu $

class Log_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	//获取日志列表或个数 $where参数为数组，之间AND关系。$order数组。$limit数组array(offset,limit<=100)。$result='data'/'num'
	public function get_all_log($where, $order_by=array(), $limit=array(0,20), $result='data')
	{
		if ($where)
		{
			$this->db->where($where);
		}
		if (is_array($order_by) && !empty($order_by))
		{
			foreach ($order_by as $k=>$v)
			{
				$this->db->order_by($k, $v);
			}
		}
		elseif ($order_by)
		{
			$this->db->order_by($order_by);
		}
		if ($result == 'data')
		{
			if ($limit)
			{
				$this->db->limit($limit[1], $limit[0]);
			}
			return $this->db->get('rbac_log')->result_array();
		}
		else
		{
			return $this->db->count_all_results('rbac_log');
		}

	}

	//记录用户操作日志
	public function record($mastername, $actionid)
	{
		$this->load->helper('url');
		$url = uri_string();
		$get = print_r($this->input->get(), TRUE);
		$post = print_r($this->input->post(), TRUE);
		$sql = 'INSERT INTO `rbac_log` SET `mastername`=?, `actionid`=?, `url`=?, `get`=?, `post`=?, `createtime`=NOW()';
		$this->db->query($sql, array($mastername, $actionid, $url, $get, $post));
		return TRUE;
	}

	//记录日志文件 $file日志文件名 $str要记录的数据字附串 $flag url或附加标注信息
	public function log_file($file, $str, $flag='') {
		$dir = rtrim($_SERVER['SITE_LOG_DIR'], '/') . '/' . date('Ym') . '/';
		!is_dir($_SERVER['SITE_LOG_DIR']) && @mkdir($_SERVER['SITE_LOG_DIR'], 0777);
		!is_dir($dir) && @mkdir($dir, 0777);
		$file = $dir . date('Ym') . $file . '.log';
		$str = date('Y-m-d H:i:s') . " {$flag} {$str} \r\n";
		file_put_contents($file, $str, FILE_APPEND);
	}
}
