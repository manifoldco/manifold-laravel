<?php

namespace manifoldco\manifold;
use manifoldco\manifold\Cache;
use manifoldco\manifold\API;

class Core
{
    static $TOKEN           = 1;
    static $PRODUCT         = 2;
    static $PROJECT         = 3;
    static $RESOURCE        = 4;

    static $key_token       = 'manifold.token';
    static $key_product     = 'manifold.product_id';
    static $key_project     = 'manifold.project_id';
    static $key_resource    = 'manifold.resource_id';

    static $DEBUG           = false;

    public function __construct(){
        $this->token = config(self::$key_token);
        $this->product = config(self::$key_product);
        $this->project = config(self::$key_project);
        $this->resource = config(self::$key_resource);

        $this->resource_level = $this->get_resource_level();
        $this->api = new API($this->token);
    }

    public function load_configs(){
        $configs = $this->load_data();
        collect($configs)->each(function($value, $key){
            config([$key => $value]);
        });
        return $configs;
    }

    public function test(){
        return $this->api->test_credentials();
    }

    public function refresh(){
        $cache_key = $this->cache_key();
        self::debug('deleting');
        Cache::delete($cache_key);
        return $this->load_configs();
    }

    public function load_data(){
        $cache_key = $this->cache_key();
        if(Cache::expired($cache_key)){
            self::debug('SERVER');
            $fresh_data = $this->get_fresh_data();
            Cache::put($cache_key, $fresh_data);
            return $fresh_data;
        }else{
            self::debug('CACHE');
            return Cache::get($cache_key);
        }
    }

    public function get_resource_level(){

        if($this->token && $this->resource){
            return self::$RESOURCE;
        }elseif($this->token && $this->project){
            return self::$PROJECT;
        }elseif($this->token && $this->product){
            return self::$PRODUCT;
        }elseif($this->token){
            return self::$TOKEN;
        }
        return null;
    }

    public function get_fresh_data(){
        switch ($this->resource_level) {
            case self::$TOKEN:
            case self::$PRODUCT:
            case self::$PROJECT:
                $configs = [];
                $resources = $this->api->resources($this->query_string());
                $resources->each(function($resource) use(&$configs){
                    $configs = array_merge($configs, $this->api->load_resource($resource));
                });
                return $configs;
                break;
            case self::$RESOURCE:
                return $this->api->load_resource($this->resource);
                break;

            default:
                return null;
                break;
        }
    }

    public function cache_key(){
        switch ($this->resource_level) {
            case self::$TOKEN:
                self::debug('TOKEN');
                return 'manifold.' . md5($this->token);
                break;
            case self::$PRODUCT:
                self::debug('PRODUCT');
                return 'manifold.' . md5($this->token . $this->product);
                break;
            case self::$PROJECT:
                self::debug('PROJECT');
                return 'manifold.' . md5($this->token . $this->project);
                break;
            case self::$RESOURCE:
                self::debug('RESOURCE');
                return 'manifold.' . md5($this->token . $this->resource);
                break;
            default:
                return null;
                break;
        }
    }

    public function query_string(){
        switch ($this->resource_level) {
            case self::$TOKEN:
                return null;
                break;
            case self::$PRODUCT:
                return 'product_id=' . $this->product;
                break;
            case self::$PROJECT:
                return 'project_id=' . $this->project;
                break;
            case self::$RESOURCE:
                return null;
                break;
            default:
                return null;
                break;
        }
    }

    public static function debug($text){
        if(self::$DEBUG){
            echo "\n$text\n";
        }
    }
}
