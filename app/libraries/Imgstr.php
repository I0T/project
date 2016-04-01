<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
class Imgstr {

	public $imgkey = 'go2o3dco';

	public function createimage($str, $fontsize=12, $fontcolor='#DD4B39')
	{
 		$out = $this->bluview_decrypt($str, $this->imgkey);
		// create a 100*30 image
		$im = imagecreate(($fontsize*strlen($out)), ($fontsize+6));

		// white background and blue text
		$bg = imagecolorallocate($im, 255, 255, 255);
		$fc = $this->html2rgb($fontcolor);
		$textcolor = imagecolorallocate($im, $fc[0], $fc[1], $fc[2]);//DD4B39
		// write the string at the top left
		imagettftext($im, $fontsize, 0, 3, $fontsize+3, $textcolor, __DIR__.'/captcha/fonts/VeraSansBold.ttf', $out);
		
		// output the image
		header("Content-type: image/png");
		imagepng($im);
		imagedestroy($im);
	}
	
	public function getstr($str)
	{
		$key = $this->imgkey;
		return urlencode($this->bluview_encrypt($str, $key));
	}

	protected function bluview_encrypt($e_string, $password)
	{
		$password = base64_encode($password);
		$count_pwd = strlen($password);
		$pwd = 0;
		for($i = 1;$i<$count_pwd;$i++) {
			$pwd+=ord($password{$i});
		}
		
		$e_string = base64_encode($e_string);
		$count = strlen($e_string);
		$asciis = '';
		for($i = 0;$i<$count;$i++) {
			$asciis.=(ord($e_string{$i})+$pwd)."|";
		}
		$asciis = base64_encode($asciis);
		return $asciis;
	}
	
	protected function bluview_decrypt($e_string, $password)
	{
		$password = base64_encode($password);
		$count_pwd = strlen($password);
		$pwd = 0;
		for($i = 1;$i<$count_pwd;$i++) {
			$pwd+=ord($password{$i});
		}
		
		$e_string = base64_decode($e_string);
		$contents = explode("|",$e_string);
		$count = count($contents);
		$infos = '';
		for ($i=0;$i<$count;$i++){
			$infos.=chr($contents[$i]-$pwd);
		}
		$asciis = base64_decode($infos);
		
		return $asciis;
	}
	
	protected function html2rgb($color,$returnstring=false){
	    if ($color[0] == '#') 
	       $color = substr($color, 1);
	    if (strlen($color) == 6)
	       list($r, $g, $b) = array($color[0].$color[1],
					 $color[2].$color[3],
					 $color[4].$color[5]);
	    elseif (strlen($color) == 3)
		list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
	    else
		return false;
	    //$key = 1/255; // use this to get a range from 0 to 1 eg: (0.5, 1, 0.1)
	    $key = 1; // use this for normal range 0 to 255 eg: (0, 255, 50)
	    $r = hexdec($r)*$key;
	    $g = hexdec($g)*$key;
	    $b = hexdec($b)*$key;
	    if($returnstring){
		return "{rgb $r $g $b}";
	    }else{
		return array($r, $g, $b);
	    }
	}
}

?>