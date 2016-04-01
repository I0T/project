<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//ldap config
$config['rbac_ldap'] = array(
	'host' => $_SERVER['SITE_LDAP_HOST'],
	'port' => $_SERVER['SITE_LDAP_PORT'],
	'user' => $_SERVER['SITE_LDAP_USER'],
	'pass' => $_SERVER['SITE_LDAP_PASS'],
	'dn' => 'OU=优信拍,DC=uxin,DC=youxinpai,DC=com',
);

//不需要登录的资源模块
$config['notneedlogin'] = array(
	'cron',
	'area',
	'api',
	'index',
	'user',
	'captcha',
	'error',
	'',
	array('admin/manager', 'getpros'),
);

//不需要认证的 array(模块,方法)
$config['notneedauth'] = array(
	array('', ''),
	array('home', 'keepalive'),
	array('home', 'index'),
	array('home', 'top'),
	array('home', 'menu'),
	array('home', 'bar'),
	array('home', 'main'),
	array('home', 'useredit'),
	array('home', 'footer'),
	array('home', 'test'),
	array('home', ''),
);

//需要记录日志的操作(对应 rbac_functioin方法名)
$config['rbac_log_function'] = array(
	'add',
	'edit',
	'enable',
	'delete',
	'send',
);

//数据资源类型列表
$config['rbac_restype'] = array(
	'bigarea' => '大区',
	'city' => '城市',
	'market' => '场地',
	'shop' => '车易卖门店',
);

//功能列表，类定义。同时显示到菜单，顺序决定菜单中排序。
$config['rbac_class'] = array(

	'cp_publish' => '拍卖管理',
	'cp_user' => '会员管理',

	//系统
	'master' => '用户管理',
	'role' => '角色管理',
	'action' => '系统管理',
	'log' => '日志管理',
	//保证金
	'cp_margin'=>'保证金管理',

);

//操作列表，方法名。
$config['rbac_function'] = array(
	'view' => '浏览',
	'viewdetail' => '查看详情',
	'add' => '添加',
	'edit' => '修改',
	'enable' => '启用/禁用',
	'delete' => '删除',
	'send' => '发送短信',
	'audit' => '审核',
	'export' => '导出',
	'stats' => '统计',
	'set' => '设置',

	'save' => '保存',
);


