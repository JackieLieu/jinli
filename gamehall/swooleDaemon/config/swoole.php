<?php
$config =  array(
    'swoole' => array(
        'master_pid' => 'data/run/master.pid',
        'manager_pid' => 'data/run/manager.pid',
        'run_log' => 'data/logs/swoole.log',
        'data_log' => 'data/logs/data.log',
        'game_base_dir' => '/data/www/mobgi_housead/branches/mobgi_housead_200170629',
    ),
);
return $config;