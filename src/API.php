<?php

namespace manifoldco\manifold;

class API
{
    static $URL = 'https://api.marketplace.manifold.co/v1/';
    static $VERSION = '0.0.0';

    public function __construct($token){
        $this->token = $token;
    }

    public function resources($query_string = null){
        $url = 'resources';
        $url .= $query_string ? '?' . $query_string : '';
        $resources = $this->server_request($url);

        $response = [];

        if(!is_null($resources)){
            $resources = collect(json_decode($resources));
            $resources->each(function($resource) use(&$response){
                if($resource && isset($resource->id) && isset($resource->type) && $resource->type == 'resource'){
                    $response[] = $resource->id;
                }
            });
        }

        return collect($response);
    }

    public function load_resource($resource_id){
        $resources = $this->server_request('credentials?resource_id=' . $resource_id);

        $response = [];

        if(!is_null($resources)){
            $resources = collect(json_decode($resources));
            $resources->each(function($resource) use(&$response){
                if($resource && isset($resource->body) && isset($resource->body->values)){
                    collect($resource->body->values)->each(function($value, $key) use(&$response){
                        $response[$key] = $value;
                    });
                }
            });
        }

        return empty($response) ? null : $response;
    }

    public function server_request($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$URL . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $this->token]);
        curl_setopt($ch, CURLOPT_USERAGENT, 'php-manifold-laravel-' . self::$VERSION );

        $response = curl_exec($ch);
        $curl_error = curl_errno($ch);
        curl_close($ch);

        return $curl_error ? null : $response;
    }

    public function test_credentials(){
        $response = json_decode($this->server_request('resources'));
        // dd($response);
        if((isset($response->type) && $response->type == 'unauthorized') || is_null($response)){
            return false;
        }else{
            return true;
        }
    }
}
