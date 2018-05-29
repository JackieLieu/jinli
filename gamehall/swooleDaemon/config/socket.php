<?php
$config =  array(
    'socket' => array(
        'host' => '127.0.0.1', //socket 监听ip
        'port' => 8088, //socket 监听端口
        'work_mode' => 3,
        'task_ipc_mode' => 3,
        // swoole server config
        'daemonize' => 1, //是否开启守护进程
        'worker_num' => 8,
        'max_request' => 0,
        'max_conn' => 1000,
        'dispatch_mode' => 3,
        'task_worker_num' => 10,
        'open_length_check' => true,
        'package_length_offset' => 0,
        'package_body_offset' => 4,
        'package_length_type' => 'N',
    ),
);
return $config;
