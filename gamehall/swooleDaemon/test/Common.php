<?php

class Common {

    private $client;
    private static $_instance;
    
    private static function getInstance() {
        if(! self::$_instance) {
            self::$_instance = new Common();
        }
        return self::$_instance;
    }
    
    public static function execute($task, $method, $params = array()) {
        $instance = self::getInstance();
        $result = $instance->send($task, $method, $params);
        var_dump($result);
    }
    
    private function __construct() {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP);
        $this->client->connect("127.0.0.1", 8888);
    }
    
    private function send($task, $method, $params) {
        $message = array();
        if($this->client && $this->client->isConnected()) {
            $data = $this->getData($task, $method, $params);
            $message = $this->pack($data);
            $this->client->send($message);
            $message = $this->client->recv();
            $message = $this->unpack($message);
        }
        return $message && $message['data'];
    }
    
    private function unpack($data) {
        if(!$data) return false;
        $pack = unpack("N", $data);
        $length = $pack[1];
        $msg = substr($data, - $length);
        return json_decode($msg, true);
    }
    
    private function pack($data) {
        $message = json_encode($data);
        return pack("N" , strlen($message) ). $message;
    }
    
    private function getData($class, $method, $params) {
        $tmp = array('task' => $class, 'method' => $method, 'args' => $params);
        $data = array('op' => 'GAME', 'data' => $tmp);
        return $data;
    }
}

