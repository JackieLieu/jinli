<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

defined('THINK_PATH') or exit();

/**
 * Redis缓存驱动 
 * 要求安装phpredis扩展：https://github.com/nicolasff/phpredis
 * @category   Extend
 * @package  Extend
 * @subpackage  Driver.Cache
 * @author    尘缘 <130775@qq.com>
 */
class CacheRedis extends Cache {

    /**
    * 构造函数
    * @param array $options 缓存参数
    * @access public
    */
    public function __construct($options=array()) {
        if ( !extension_loaded('redis') ) {
            throw_exception(L('_NOT_SUPPERT_').':redis');
        }
        if(empty($options)) {
            $options = array (
                'host'        => C('REDIS_HOST') ? C('REDIS_HOST') : '127.0.0.1',
                'port'        => C('REDIS_PORT') ? C('REDIS_PORT') : 6379,
                'timeout'      => C('DATA_CACHE_TIMEOUT') ? C('DATA_CACHE_TIMEOUT') : false,
                'persistent'    => false,
            );
        }
        $this->options =  $options;
        $this->options['expire'] =  isset($options['expire']) ? $options['expire'] : C('DATA_CACHE_TIME');
        $this->options['prefix'] =  isset($options['prefix']) ? $options['prefix'] : C('DATA_CACHE_PREFIX');       
        $this->options['length'] =  isset($options['length']) ? $options['length'] : 0;       

        $this->handler  = new Redis;
        $func = $options['persistent'] ? 'pconnect' : 'connect';
        $options['timeout'] === false ? $this->handler->$func($options['host'], $options['port']) : $this->handler->$func($options['host'], $options['port'], $options['timeout']);
        $this->handler->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
        $this->handler->setOption(Redis::OPT_PREFIX, $this->options['prefix']);
    }

    /**
    * 是否连接
    * @access private
    * @return boolen
    */
    private function isConnected() {
        return '+PONG' == $this->handler->ping() ? true : false;
    }

    /**
    * 读取缓存
    * @access public
    * @param string $name 缓存变量名
    * @return mixed
    */
    public function get($name) {
        N('cache_read',1);
        return $this->handler->get($name);
    }

    /**
    * 写入缓存
    * @access public
    * @param string $name 缓存变量名
    * @param mixed $value  存储数据
    * @param integer $expire  有效时间（秒）
    * @return boolen
    */
    public function set($name, $value, $expire = null) {
        N('cache_write',1);
        if(is_null($expire)) {
            $expire  =  $this->options['expire'];
        }
        if(is_int($expire)) {
            $result = $this->handler->setex($name, $expire, $value);
        }else{
            $result = $this->handler->set($name, $value);
        }
        if($result && $this->options['length']>0) {
            // 记录缓存队列
            $this->queue($name);
        }
        return $result;
    }

    /**
    * 删除缓存
    * @access public
    * @param string $name 缓存变量名
    * @return boolen
    */
    public function rm($name) {
        return $this->handler->delete($name);
    }

    /**
    * 清除缓存
    * @access public
    * @return boolen
    */
    public function clear() {
        return $this->handler->flushDB();
    }
    
    /**
    * 魔术方法，phpRedis中所包含的所有操作均可以通过魔术方法直接调用
    * @access public
    * @return mixed
    */
    function __call($method, $args)
    {
        return call_user_func_array(array($this->handler, $method) , $args);
    }
}