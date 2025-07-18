<?php

Class Memc extends MY_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->model('PDOhandler');
    }

    public function get($sql, $params,  $key = null, $expire = 360000, $action = 'fetch')
    {
        if(empty($key)){
            $key = $this->buildKey($sql, $params);
        }

        Debugger::debug('checking cache for ' . $key, '', false, 'memc');
        $result = $this->cache->get($key);

        if(empty($result)){
            Debugger::debug('not found, creating', '', false, 'memc');
            // $key not in cache, get from db
            $result = $this->PDOhandler->query($sql, $params, $action);
            $this->put($key, $result, $expire);
        } else {
            Debugger::debug($result, 'found', false, 'memc');
        }

        return $result;
    }

    public function put($key, $result, $expire)
    {
        $this->cache->memcached->save($key, $result, $expire);
    }

    private function buildKey($sql, $params = null)
    {
        $key = $sql;

        if($params){
            foreach($params as $k => $v){
                $key .= $k . '|' . $v;
            }
        }

        return md5($key);
    }

    public function flush($key = null)
    {
        if(isset($key)){
            $this->cache->delete($key);
        } else {
            $this->cache->clean();
        }
    }
}
