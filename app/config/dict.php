<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * 为防止变量污染，此配置key必须以dict_开头。
 */
$config['dict_baiduditu_key'] = 'D04c2098139d49a2a3e6abab8bd8caff';

//手机归属地接口
$config['dict_mobile_api'] = 'http://opendata.baidu.com/api.php?co=&resource_id=6004&ie=utf8&oe=gbk&format=json&tn=baidu&query=';

//IP地址查询接口(1	202.116.0.0	202.116.31.255	中国	广东	广州		教育网	学校	暨南大学教育网)制表符分隔
$config['dict_ip_api'] = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=text&encoding=utf-8&ip=';

/**
 * 优信拍 接口地址定义
 * @var unknown_type
 */
$config['dict_domain_yxpapi'] = 'http://'.trim($_SERVER['SITE_DOMAIN_AUCTIONSERVICE'], ' /');
/**
 * 查客接口域名
 * @var unknown_type
 */
$config['dict_domain_chake'] = 'http://'.trim($_SERVER['SITE_DOMAIN_CHAKE'], ' /');
//$config['dict_domain_chake'] = 'http://www.chake.net';

/**
 * 优信拍域名
 * @var unknown_type
 */
$config['dict_domain_yxp'] = 'http://'.trim($_SERVER['SITE_DOMAIN_YXP'], ' /');

//padapi
if(ENVIRONMENT == 'production') {
	$config['dict_domain_padapi'] = 'http://padapi.youxinpai.com/';
} else {
	$config['dict_domain_padapi'] = 'http://padapi.test.youxinpai.com/';
}

//一汽轿车经销商父id
if(ENVIRONMENT == 'production') {
    $config['dict_chrysler_1'] = 112144;
    $config['dict_chrysler_2'] = 112144;
    $config['dict_chrysler_3'] = 112144;
    $config['dict_chrysler_10'] = 112144;
    $config['dict_chrysler_11'] = 112144;
    $config['dict_chrysler_12'] = 112144;
} else {
    $config['dict_chrysler_1'] = 8379;
    $config['dict_chrysler_2'] = 8379;
    $config['dict_chrysler_29'] = 8379;
    $config['dict_chrysler_30'] = 40864;
    $config['dict_chrysler_31'] = 40864;
    $config['dict_chrysler_32'] = 40864;
    $config['dict_chrysler_33'] = 40864;
    $config['dict_chrysler_33'] = 40864;
    $config['dict_chrysler_34'] = 40864;
    $config['dict_chrysler_57'] = 112144;
    $config['dict_chrysler_58'] = 112144;
    $config['dict_chrysler_dealer_id3'] = 27319;
}
// $config['dict_faw_parent_id'] = 272;