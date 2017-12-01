<?php

use Illuminate\Foundation\Testing\TestCase;

use manifoldco\manifold\Core;
use manifoldco\manifold\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;

class APITest extends TestCase
{

    public function createApplication()
    {
        require_once __DIR__ . '/getApp.php';
        $app = require manifold_test_app_path();

        $app->make(Kernel::class)->bootstrap();

        $this->core = new Core;
        if(isset($this->core->token)){
            $this->api = new API($this->core->token);
            $this->resources = $this->api->resources();
        }

        return $app;
    }

    public function testConstruct(){
        $this->assertNotNull($this->api);
    }

    public function testServerRequest(){
        $this->assertNotNull($this->api->server_request('resources'));
    }

    public function testResources(){
        $this->assertNotNull($this->resources);
        $this->assertSame($this->resources->isEmpty(), false);
    }

    public function testLoadResource(){
        $this->assertNotNull(
            $this->api->load_resource(
                $this->resources->first()
            )
        );
    }

    public function testTestCredentials(){
        $this->assertInternalType('boolean', $this->api->test_credentials());
    }

}
