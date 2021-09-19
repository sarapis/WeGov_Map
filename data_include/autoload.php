<?php 
set_time_limit(0);
include 'ini.php';
foreach (scandir(__DIR__) as $fn)
	if (preg_match('~\.php$~', $fn) && !preg_match('~(autoload)\.php$~', $fn))
		include $fn;
	
define('VERBOSE_CURL_PARAM', false);