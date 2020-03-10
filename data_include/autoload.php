<?php 
include 'ini.php';
foreach (scandir(__DIR__) as $fn)
	if (preg_match('~\.php$~', $fn) && !preg_match('~(autoload)\.php$~', $fn))
		include $fn;