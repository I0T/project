<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
class Images {

	//显示查客图片地址
	/*
	 * 1:   1030*752 原图    后缀名.jpg
	 * 2:   640*480 原图    后缀名_big.jpg
	 * 3:   1280*900 原图    后缀名_bigger.jpg
	 * 4:   250*187 原图    后缀名_middle.jpg
	 * 5:   100*75 原图    后缀名_small.jpg
	 * 6:   80*60 原图    后缀名_smaller.jpg
	*/
	public function get_chake_image_url($pic, $postfix)
	{
 		$prefix = 'http://img1.youxinpai.com/Upload/UppUpload/CheckAuto/';
		//$prefix = 'http://test.checkauto.com.cn/UserData/Images/';
		$p = strrpos($pic, '.');
		return $prefix.substr($pic, 0, $p).$postfix.substr($pic,$p);
		
	}
}

?>