<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Fn {

	/**
	 * 判断并转换字符编码，需 mb_string 模块支持。
	 *
	 * @param mixed $str 数据
	 * @param string $encoding 要转换的编码类型
	 * @return mixed 转换过的数据
	 */
	public static function encoding_convert($str, $encoding = 'UTF-8') {
		if (is_array($str)) {
			$arr = array();
			foreach ($str as $key => $val) {
				$arr[$key] = self::encoding_convert($val, $encoding);
			}
			return $arr;
		}
		$_encoding = mb_detect_encoding($str, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
		if ($_encoding == $encoding) {
			return $str;
		}
		try {
			$str = @mb_convert_encoding($str, $encoding, $_encoding);
		} catch(Exception $e) {
			//nothing todo
		}
		return $str;
	}

	/**
     * 用 mb_strimwidth 来截取字符，使中英尽量对齐。
     *
     * @param string $str
     * @param int $start
     * @param int $width
     * @param string $trimmarker
     * @return string
     */
    public static function wsubstr($str, $start, $width, $trimmarker = '...') {
        $_encoding = mb_detect_encoding($str, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
		$_encoding = $_encoding ? $_encoding : 'UTF-8';
        return mb_strimwidth($str, $start, $width, $trimmarker, $_encoding);
    }

	//拼图片完整路径
	public static function yxp_img($pic, $t='') {
		$domain = 'http://img3.youxinpai.com/';
		$info = pathinfo($pic);
		if($t) {
			$info['filename'] .= '_0' . intval($t);
		}
		return $domain . trim($info['dirname'], '/') . '/' . $info['filename'] . '.' . $info['extension'];
	}

	//发送邮件 多人$to用英文逗号分隔
	public static function yxp_email($to, $subject, $message, $type='text') {
		$CI = &get_instance();
		$CI->load->library('email');
		$CI->email->from('uxin@e.xin.com', 'chrysler');
		$CI->email->to($to);
		$CI->email->set_mailtype($type); //邮件内容格式
		$CI->email->subject($subject);
		$CI->email->message($message);
		return $CI->email->send();
	}


}
