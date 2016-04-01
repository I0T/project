<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 /**
  * 发送邮件配置
  */
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'emailserver.chinacloudapp.cn';
$config['smtp_user'] = 'uxin@e.xin.com';
$config['smtp_pass'] = 'u3H8ya36NL';

/*$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.exmail.qq.com';
$config['smtp_user'] = 'yxp@e.youxinpai.com';
$config['smtp_pass'] = '1qaz!QAZ';*/
$config['smtp_port'] = 25;
$config['smtp_timeout'] = 5;
$config['charset'] = 'utf-8';

$config['crlf']="\r\n";
$config['newline']="\r\n";