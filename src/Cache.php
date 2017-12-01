<?php

namespace manifoldco\manifold;
use Illuminate\Support\Facades\Cache as SystemCache;
use manifoldco\manifold\Core;

class Cache
{
    static $CACHE_TIME      = 30; //24*60

    public static function expired($key){
        return !SystemCache::has($key);
    }

    public static function results($cache_key, $callback){
        $configs = [];
        if(SystemCache::has($cache_key)){
            Core::debug('CACHE');
            $configs = (array) json_decode(SystemCache::get($cache_key));
        }else{
            Core::debug('SERVER');
            if(is_callable($callback)){
                $configs = $callback();
                SystemCache::put($cache_key, json_encode($configs), self::$CACHE_TIME);
            }
        }
        return $configs;
    }

    public static function get($key){
        return (array) json_decode(SystemCache::get($key));
    }

    public static function put($key, $data){
        SystemCache::put($key, json_encode($data), self::$CACHE_TIME);
    }

    public static function delete($key){
        SystemCache::pull($key);
    }
}
