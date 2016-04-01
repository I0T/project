<?php

//取多维数据中某字段的值
if ( ! function_exists('array_muliti_field'))
{
	function array_muliti_field($array, $field)
	{
		$resp = array();
		foreach($array as $k => $v) {
			if(is_array($field)) {
				foreach($field as $f) {
					if(isset($v[$f]) && $v[$f] !== null) {
						$resp[$f][$v[$f]] = $v[$f];
					}
				}
			} elseif(isset($v[$field]) && $v[$field] !== null){
				$resp[] = $v[$field];
			}
		}
		return $resp;
	}
}
/*
  *  将多为数组中的某一个元素作为键名
 * $array = array(0=>array('id'=>10,'title'=>'t10'),1=>array('id'=>11,'title'=>'t11'));
 * $array = array_set_key($array, 'id');
 * array(10=>array('id'=>10,'title'=>'t10'),11=>array('id'=>11,'title'=>'t11'));
*/
if ( ! function_exists('array_set_key'))
{
	function array_set_key($array, $field)
	{
		$resp = array();
		foreach($array as $k => $v)
		{
			$resp[$v[$field]] = $v;
		}
		return $resp;
	}
}

