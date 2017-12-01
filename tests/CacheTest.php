<?php

use Illuminate\Foundation\Testing\TestCase;

use manifoldco\manifold\Cache;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;

class CacheTest extends TestCase
{

    public function createApplication()
    {
        require_once __DIR__ . '/getApp.php';
        $app = require manifold_test_app_path();

        $app->make(Kernel::class)->bootstrap();

        $this->cache_key = 'test-cache-key';

        return $app;
    }

    public function testGetPutDelete(){
        $test_array = ['item-0', 'item-1'];

        Cache::put($this->cache_key, $test_array);
        $cache_value = Cache::get($this->cache_key);
        $this->assertSame($cache_value[0], 'item-0');

        Cache::delete($this->cache_key);
        $cache_value = Cache::get($this->cache_key);
        $this->assertEmpty($cache_value);
    }

    public function testResults(){
        $test_array = ['item-0', 'item-1'];
        Cache::put($this->cache_key, $test_array);

        $results = Cache::results($this->cache_key, function(){
            return ['item-2', 'item-3'];
        });
        $this->assertSame($results[0], 'item-0');
        Cache::delete($this->cache_key);

        $results = Cache::results($this->cache_key, function(){
            return ['item-4', 'item-5'];
        });
        $this->assertSame($results[0], 'item-4');
        Cache::delete($this->cache_key);
    }



}
