<?php

/*
 *---------------------------------------------------------------
 * 优信拍 所有cron 入口 文件
 *---------------------------------------------------------------
 *
 */

$yxp_file = '/usr/local/nginx/conf/cron.conf';
date_default_timezone_set('PRC');

if(file_exists($yxp_file)){
	$yxp_conf = parse_ini_file($yxp_file);
	foreach ($yxp_conf as $k=>$v)
	{
		$_SERVER[$k] = $v;
	}
	require dirname(__FILE__).'/index.php';

}
else
{
	echo 'conf file not exits.';
}
unset($yxp_file,$yxp_conf);
