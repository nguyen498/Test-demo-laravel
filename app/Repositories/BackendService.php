<?php

namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class BackendService extends ServiceProvider
{
    public function register(){
        $this->app->bind(
            ''
        );
    }
}
