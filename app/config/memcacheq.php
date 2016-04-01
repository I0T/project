<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$v = explode(':', $_SERVER['SITE_MEMCQ_SERVER']);
$config['memcacheq'] = array(
	'hostname' => $v[0],
	'port' => $v[1],
);
unset($v);

/**
 *  队列名称列表，请先将新建的队列名称加入此列表，便于管理和维护
 *  不在此列表中的队列不可用
 * @var unknown_type
 */
$config['queuelist'] = array(
	"SMS_YOUXINPAI",//优信拍短信队列
);
