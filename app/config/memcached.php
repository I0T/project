<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$s = explode(',', $_SERVER['SITE_MEMC_SERVER']);
foreach ($s as $v)
{
    $v = explode(':', $v);
    $config['memcached'][] = array(
        'hostname' => $v[0],
        'port'     => $v[1],
        'weight'   => 1,
    );
}

/*
$config['memcached'][] = array(
    'hostname' => $_SERVER['SITE_MEMC_HOST'],
    'port'     => $_SERVER['SITE_MEMC_PORT'],
    'weight'   => 1,
);
*/
