<?php
if (!defined('BASE_PATH')) exit('Access Denied!');
$config = array(
	'test' => array(
			'default'=>array(
				'adapter' => 'PDO_MYSQL',
				'host'=>'testpushdb01.mysql.aliyun.com',
				'username'=>'preloadw',
				'password'=>'ilwf39_1felafg',
				'dbname'=>'preload',
				'displayError'=>1
			)
	),
	'product' => array(
			'default'=>array(
				'adapter' => 'PDO_MYSQL',
				'host'=>'prodecdb01.mysql.aliyun.com',
				'username'=>'prodacdbw',
				'password'=>'wsadea234',
				'dbname'=>'prodacdb',
				'displayError'=>0
			)
	),
	'develop' => array(
			'default'=>array(
				'adapter' => 'PDO_MYSQL',
				'host'=>'127.0.0.1',
				'username'=>'root',
				'password'=>'root',
				'dbname'=>'ac',
				'displayError'=>1
			)
	)
);
return defined('ENV') ? $config[ENV] : $config['product'];