<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 11-19 019
 * Time: 16:21
 */

namespace Xin6841414\LaravelEnv;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(LaravelEnv::class, function(){
            return new LaravelEnv();
        });
        $this->app->alias(LaravelEnv::class, 'laravel-env');
    }

    public function provides()
    {
        return [LaravelEnv::class, 'laravel-env'];
    }
}