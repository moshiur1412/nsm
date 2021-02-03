<?php

namespace App\Observers;

use App\Models\Award;
use App\Models\Config;

class AwardObserver
{
    /**
     * Handle the temple service "created" event.
     *
     * @param  \App\Award  $award
     * @return void
     */
    public function created(Award $award)
    {
        $config =  Config::first();
        $next_no = $config->next_award_receipt;

        $award->receipt = $next_no;
        $award->update();

        $config->next_award_receipt = $next_no + 1;
        $config->update();
    }
}
