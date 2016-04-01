<?PHP

// * $Id: user_model.php 25450 2014-07-29 07:53:39Z liqinggai $

class User_model extends YXP_Model
{
	public function __construct()
	{
		parent::__construct('user', 'userid');
		if (!session_id()) {
			session_start();
		}
	}

	//用户是否登录，TRUE/FALSE
	public function is_login()
	{   
		$session_id = $this->get_sessionid();
		$flag = FALSE;
		if (isset($_SESSION['user']['name'])){
			$user =  $this->get_row(array('name'=>$_SESSION['user']['name']));
			if($user['session_id'] == $session_id && $_SESSION['user']['time'] > time() - 1800){
				$_SESSION['user']['time'] = time();
				$flag = TRUE;
			}
		}
		return $flag;
	}

	public function get_sessionid()
	{
		return session_id();
	}

	//用户登录信息，未登录返回FALSE
	public function login_info()
	{
		if ($this->is_login())
		{
			return $_SESSION['user'];
		}
		else
		{
			return FALSE;
		}
	}

	/*
	 * 获取当前登录用户数据权限
	 * param -
	 * return array(restype => array(数据id,...),...)
	*/
	public function get_my_res()
	{
		$user = $this->login_info();
		return $user['res'];
	}
	//登录验证
	public function check($name, $password)
	{
		if ($name == '' || $password == '') return FALSE;
		$user = $this->get_row(array('username'=>$name, 'password'=>md5($password)));
		if($user && isset($user['name']) && $user['status'] >= 0) {
			$this->set_login_session($user);
			return TRUE;
		}
		return FALSE;
	}

	//判断 登陆后跳转
	public function login_redirect() {
		if($this->is_login()) {
			$user = $this->user_model->login_info();
			$role = array(
				0 => '/admin/',
				1 => '/admin/',
				2 => '/dealer/',
				3 => '/dealer/',
                4 => '/dealer/',
			);
			if(!isset($role[$user['type']])) {
				$this->destroy_session();
				show_error('对不起，用户权限设置错误！', 200, '权限错误');
			}
			//$this->config->set_item('url_suffix', '.html');
			redirect($role[$user['type']]);
		}
	}

	//登录成功后种用户session
	public function set_login_session($user)
	{
		$session_id = $this->get_sessionid();
		$this->user_model->update(array("session_id"=>$session_id),array('name'=>$user['name']));
		$user['time'] = time();
		$_SESSION['user'] = $user;
	}

	//清空session
	public function destroy_session()
	{
		$_SESSION['user'] = null;
		unset($_SESSION['user']);
	}

	public function get_dealer_info($user_id)
	{

		return	$this->db->where('user_id', $user_id)->get('dealer')->row_array();
	}


}
