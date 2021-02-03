<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Applicant;
use App\Models\Judge;
use App\Models\Subsidy;
use App\Models\Award;


class InitializeReceipt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init_receipt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'gives receipt same as id';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $apps = Subsidy::all();
        foreach ($apps as $app) {
            $app->receipt = $app->id;
            $app->update();
        }
        $apps = Award::all();
        foreach ($apps as $app) {
            $app->receipt = $app->id;
            $app->update();
        }

    }

}
