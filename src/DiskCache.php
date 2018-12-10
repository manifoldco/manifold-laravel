<?php

namespace manifoldco\manifold;
use manifoldco\manifold\Core;

class DiskCache{
    static function read_file(){
        if(!is_readable(storage_path('.manifold.cache.key'))){
            Core::debug("cache file is not readable");
            return;
        }
        $content = file_get_contents(storage_path('.manifold.cache.key'));
        $json = json_decode($content);
        return $json;
    }

    public static function has($key){
        $json = static::read_file();
        if(is_object($json)){
            if(isset($json->$key)){
                if(isset($json->$key->expires)){
                    if($json->key->expires < time()){
                        if(isset($json->$key->value)){
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    public static function get($key){
        $json = static::read_file();
        if(is_object($json)){
            if(isset($json->$key)){
                if(isset($json->$key->expires)){
                    if($json->$key->expires > time()){
                        if(isset($json->$key->value)){
                            return $json->$key->value;
                        }
                    }
                }
            }
        }
        return null;
    }

    public static function put($key, $data, $time){
        if(!is_writable(storage_path('.manifold.cache.key'))){
            Core::debug("cache file is not readable");
            return;
        }

        $json = static::read_file();
        if(is_null($json) || empty($json))
            $json = (object)[];
        if(isset($json->$key)){
            if(isset($json->$key->expires)){
                if($json->$key->expires > time() + ($time*60)){
                    return;
                }
            }
        }
        $json->$key = [
            'value' => $data,
            'expires' => time() + ($time*60)
        ];
        $cache_data = json_encode($json);
        file_put_contents(storage_path('.manifold.cache.key'), $cache_data);
    }

    public static function pull($key){
        if(!is_writable(storage_path('.manifold.cache.key'))){
            Core::debug("cache file is not readable");
            return;
        }

        $json = static::read_file();
        if(is_null($json) || empty($json))
            $json = (object)[];
        if(isset($json->$key)){
            unset($json->$key);
        }

        $cache_data = json_encode($json);
        file_put_contents(storage_path('.manifold.cache.key'), $cache_data);
    }
}
