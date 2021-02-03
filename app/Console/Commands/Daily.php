<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Applicant;
use App\Models\Judge;
use App\Models\Subsidy;
use App\Models\Award;


class Daily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Batch';

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
      //\Log::info("test command");
      $this->annulUser();
      $this->annulJudge();
      $this->expireSubsidy();
      $this->expireAward();
      $this->clean();

    }
    private function annulUser() {
      try {
        $willAnnulUser = Applicant::where('valid',1)->where('expiration_date','<',date('Y-m-d H:i:s', strtotime("yesterday")));
        $willAnnulUserIds = [];
        $willAnnulUserIds = $willAnnulUser->pluck('id');
        $willAnnulUser->delete();
        \Log::info("users deleted:".json_encode($willAnnulUserIds));
      } catch (\Exception $e) {
        \Log::error("users deletion failed msg=".$e->getMessage()." ids=".json_encode($willAnnulUserIds));
      }
    }
    private function annulJudge() {
      try {
        $willAnnulJudge = Judge::where('valid',1)->where('login_expires_at','<',date('Y-m-d H:i:s', strtotime("yesterday")));
        $willAnnulJudgeIds = [];
        $willAnnulJudgeIds = $willAnnulJudge->pluck('id');
        $willAnnulJudge->update(['valid' => 0]);
        \Log::info("judges annulled:".json_encode($willAnnulJudgeIds));
      } catch (\Exception $e) {
        \Log::error("judges annullment failed msg=".$e->getMessage()." ids=".json_encode($willAnnulJudgeIds));
      }
    }
    private function expireSubsidy() {
      try {
            $willExpireSubsidy = Subsidy::where('valid',1)->where('state',2)->where('expiration_date','<',date('Y-m-d H:i:s', strtotime("yesterday")));
            $willExpireSubsidyIds = [];
            $willExpireSubsidyIds = $willExpireSubsidy->pluck('id');
            $willExpireSubsidy->update(['state' => 3]);
            \Log::info("subsidy judging started:".json_encode($willExpireSubsidyIds));
      } catch (\Exception $e) {
        \Log::error("subsidy judging failed msg=".$e->getMessage()." ids=".json_encode($willExpireSubsidyIds));
      }
    }
    private function expireAward() {
      try {
        $willExpireAward = Award::where('valid',1)->where('state',2)->where('expiration_date','<',date('Y-m-d H:i:s', strtotime("yesterday")));
        $willExpireAwardIds = [];
        $willExpireAwardIds = $willExpireAward->pluck('id');
        $willExpireAward->update(['state' => 3]);
        \Log::info("award judging started:".json_encode($willExpireAwardIds));
      } catch (\Exception $e) {
        \Log::error("award judging failed msg=".$e->getMessage()." ids=".json_encode($willExpireAwardIds));
      }
    }
    private function clean() {
      try {
        \Storage::disk('tmp')->deleteDirectory(date('Y'));
        \Storage::disk('public')->deleteDirectory('tmp');
        $files = \Storage::disk('tmp')->files();
        $filtered = [];
        foreach ($files as $value) {
            if (substr($value, -3) == "pdf") $filtered[] = $value;
        }
        $files = \Storage::disk('tmp')->delete($filtered);
        \Log::info("clean");
      } catch (\Exception $e) {
        \Log::error("clean failed msg=".$e->getMessage());
      }
    }

}
