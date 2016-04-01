<?php

/* 生成验证码 $Id: Ycaptcha.php 24264 2014-07-08 07:15:59Z raohongfu $ */

class Ycaptcha {

	private $yxp_captche_var = 'yxp_captche_var';

	function __construct() {
		if (!session_id()) {
			session_start();
		}
	}

	//判断 验证码 是否正确
	function verifyResult($code, $SessName) {
        $CI = &get_instance();
        $CI->load->library('session');
        $code = strtolower($code);
        if ($CI->session->userdata($SessName) == $code) {
            $CI->session->unset_userdata($SessName);
            return true;
        }
        $CI->session->unset_userdata($SessName);
        return false;
	}

	//生成随机字符串
	function getCaptchaText($length) {
		$str = 'QWERTYUPASDFGHJKLMNBVCXZqwertyupasfghjkmnbvcxz23456789';
		$ret = '';
		for($i=0; $i<$length; $i++) {
			$n = rand(0, strlen($str)-1);
			$ret .= $str{$n};
		}
		return $ret;
	}

	//显示验证码
	function CreateImage($SessName='s', $width=70, $height=30, $length=4) {
		/* Create Imagick object */
		$Imagick = new Imagick();

		/* Create the ImagickPixel object (used to set the background color on image) */
		$bg = new ImagickPixel();

		/* Set the pixel color to white */
		$bg->setColor( 'white' );

		/* Create a drawing object and set the font size */
		$ImagickDraw = new ImagickDraw();

		/* Set font and font size. You can also specify /path/to/font.ttf */
		$font = BASEPATH . 'fonts/simsun.ttc'; //字体文件
		$ImagickDraw->setFont( $font );
		$ImagickDraw->setFontSize( 20 );

		/* Create the text */
		$text = $this->getCaptchaText($length);

		$CI = &get_instance();
        $CI->load->library('session');
        $CI->session->set_userdata($SessName, strtolower($text));

		/* Create new empty image */
		$Imagick->newImage( $width, $height, $bg );

		/* Write the text on the image */
		$Imagick->annotateImage( $ImagickDraw, 5, 20, 0, $text );

		/* Add some swirl */
		$Imagick->swirlImage( 20 );

		/* Create a few random lines */
		//$ImagickDraw->line( rand( 0, 70 ), rand( 0, 30 ), rand( 0, 70 ), rand( 0, 30 ) );
		$ImagickDraw->line( rand( 0, 70 ), rand( 0, 30 ), rand( 0, 70 ), rand( 0, 30 ) );
		$ImagickDraw->line( rand( 0, 70 ), rand( 0, 30 ), rand( 0, 70 ), rand( 0, 30 ) );
		//$ImagickDraw->line( rand( 0, 70 ), rand( 0, 30 ), rand( 0, 70 ), rand( 0, 30 ) );
		//$ImagickDraw->line( rand( 0, 70 ), rand( 0, 30 ), rand( 0, 70 ), rand( 0, 30 ) );

		/* Draw the ImagickDraw object contents to the image. */
		$Imagick->drawImage( $ImagickDraw );

		/* Give the image a format */
		$Imagick->setImageFormat( 'png' );

		/* Send headers and output the image */
		header( "Content-Type: image/{$Imagick->getImageFormat()}" );
		echo $Imagick->getImageBlob( );
	}


}
















?>
