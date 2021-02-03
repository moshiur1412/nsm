<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use App\Models\Subsidy;
use App\Models\Award;
use App\Observers\SubsidyObserver;
use App\Observers\AwardObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //compose all the views....
        view()->composer('*', function ($view)
            {   if (Auth::check()) {
                $subsidy_global = Subsidy::where('user_id', Auth::user()->id)->first();
                $award_global = Award::where('user_id', Auth::user()->id)->first();
                $view->with('subsidy_global', $subsidy_global );
                $view->with('award_global', $award_global );
            }

        });
        Subsidy::observe(SubsidyObserver::class);
        Award::observe(AwardObserver::class);
    }
}
