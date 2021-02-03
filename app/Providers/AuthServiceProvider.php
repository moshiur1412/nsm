<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Award;
use App\Models\Subsidy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        \Illuminate\Support\Facades\Auth::provider('customuserprovider', function($app, array $config) {
            return new CustomUserProvider($app['hash'], $config['model']);
        });

        \Gate::define('apply-access', function(){

            $subsidy = Subsidy::where('user_id', Auth::user()->id)->first();
            $award = Award::where('user_id', Auth::user()->id)->first();

            if($subsidy == null || $award == null){
                if($award != null){
                    return false;
                }
                if($subsidy != null){
                    return false;
                }
                return true;
            }

            return false;

        });


    }
}
