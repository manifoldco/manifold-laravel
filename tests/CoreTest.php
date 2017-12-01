<?php

use Illuminate\Foundation\Testing\TestCase;

use manifoldco\manifold\Core;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;

class CoreTest extends TestCase
{

    public function createApplication()
    {
        require_once __DIR__ . '/getApp.php';
        $app = require manifold_test_app_path();

        $app->make(Kernel::class)->bootstrap();

        $this->core = new Core;

        return $app;
    }

    public function testConstruct(){
        $this->assertEquals($this->core->token, $this->core->api->token);
        $this->assertNotNull($this->core->api);
        $this->assertNotNull($this->core->token);
    }

    public function testLoadConfigs(){
        $this->assertInternalType('array', $this->core->load_configs());
    }

    public function testTest(){
        $this->assertInternalType('boolean', $this->core->test());
    }

    public function testRefresh(){
        $this->assertInternalType('array', $this->core->refresh());
    }

    public function testLoadData(){
        $this->assertInternalType('array', $this->core->load_data());
    }

    public function testGetResourceLevel(){
        $level = $this->core->get_resource_level();
        $this->assertInternalType('integer', $level);
        if(config(Core::$key_resource)){
            $this->assertEquals($level, Core::$RESOURCE);
        }elseif(config(Core::$key_project)){
            $this->assertEquals($level, Core::$PROJECT);
        }elseif(config(Core::$key_product)){
            $this->assertEquals($level, Core::$PRODUCT);
        }else{
            $this->assertEquals($level, Core::$TOKEN);
        }
    }

    public function testGetFreshData(){
        $this->assertInternalType('array', $this->core->get_fresh_data());
    }

    public function testCacheKey(){
        $key = $this->core->cache_key();
        $this->assertSame(strpos($key, 'manifold.'), 0);
        $this->assertEquals(strlen($key), 41);
    }

    public function testQueryString(){
        $original_resource_level = $this->core->resource_level;

        $this->core->resource_level = Core::$TOKEN;
        $this->assertNull($this->core->query_string());

        $this->core->resource_level = Core::$PRODUCT;
        $this->assertEquals($this->core->query_string(), 'product_id=' . $this->core->product);

        $this->core->resource_level = Core::$PROJECT;
        $this->assertEquals($this->core->query_string(), 'project_id=' . $this->core->project);

        $this->core->resource_level = Core::$RESOURCE;
        $this->assertNull($this->core->query_string());

        $this->core->resource_level = $original_resource_level;
    }
}
