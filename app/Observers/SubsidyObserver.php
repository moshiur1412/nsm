<?php

namespace App\Observers;
use App\Models\Subsidy;
use App\Models\Config;

class SubsidyObserver
{
    /**
     * Handle the temple service "created" event.
     *
     * @param  \App\Subsidy  $subsidy
     * @return void
     */
    public function created(Subsidy $subsidy)
    {
        $config =  Config::first();
        $next_no = $config->next_subsidy_receipt;

        $subsidy->receipt = $next_no;
        $subsidy->update();

        $config->next_subsidy_receipt = $next_no + 1;
        $config->update();
    }

}
