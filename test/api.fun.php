<?php
// +----------------------------------------------------------------------
// | Fly:php-cli
// +----------------------------------------------------------------------
// | Copyright (c) 2014 xuyuan All rights reserved.
// +----------------------------------------------------------------------
// | Author: xuyuan
// +----------------------------------------------------------------------
//把文件注册到系统

function load_api($load=false){	
	//FLY标准导入
	$files=array(
		'Api'		=>__DIR__ . '/Api.class.php',
		'Driver'		=>__DIR__ . '/Driver.class.php',
		'FlyCurl'	=> __DIR__ . '/FlyCurl.class.php',        
	);
	if($load){
		foreach($files as $file){
			require($file);
		}
	}
	return $files;
}






