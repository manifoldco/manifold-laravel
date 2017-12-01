<?php

//this is so the tests can be run from within the packages directory
//(development) or the vendor directory (production)
function manifold_test_app_path(){
    if(file_exists(__DIR__.'/../../../../bootstrap/app.php')){
        return __DIR__.'/../../../../bootstrap/app.php';
    }elseif(file_exists(__DIR__.'/../bootstrap/app.php')){
        return __DIR__.'/../bootstrap/app.php';
    }
}
