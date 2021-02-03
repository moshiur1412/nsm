<?php

namespace App\Listeners;

use App\Events\SubsidyRejectionNoticed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendEmailSubsidyRejectionNoticed
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SubsidyRejectionNoticed  $event
     * @return void
     */
    public function handle(SubsidyRejectionNoticed $event)
    {

      $subsidy = $event->subsidy;
      $user = $event->subsidy->user;

       $data = array(
        'subsidy' => $subsidy,
        'user' => $user,
       );

       Mail::send('mails.subsidyRejectionNoticedEmail', $data, function ($m) use ($subsidy) {
        $m->from('josei@noguchi.or.jp ', '公益財団法人 野口研究所');
        $m->to($subsidy->user->email, $subsidy->name)->subject('助成金採択結果');
    });

   }
}
